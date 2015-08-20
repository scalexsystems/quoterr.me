<?php namespace Quoterr;

/**
 * Class Author
 *
 * @package Quoterr
 * @property-read \Illuminate\Database\Eloquent\Collection|Quote[] $quotes
 * @property integer                                               $id
 * @property string                                                $name
 * @property string                                                $slug
 * @property string                                                $bio
 * @property string                                                $photo
 * @property \Carbon\Carbon                                        $created_at
 * @property \Carbon\Carbon                                        $updated_at
 * @method static \Illuminate\Database\Query\Builder|Author whereId($value)
 * @method static \Illuminate\Database\Query\Builder|Author whereName($value)
 * @method static \Illuminate\Database\Query\Builder|Author whereBio($value)
 * @method static \Illuminate\Database\Query\Builder|Author wherePhoto($value)
 * @method static \Illuminate\Database\Query\Builder|Author whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Author whereUpdatedAt($value)
 */
class Author extends Model
{
    /**
     * @type string
     */
    protected $table = 'authors';

    /**
     * @type array
     */
    protected $fillable = ['name', 'bio', 'slug', 'photo'];

    /**
     * @type array
     */
    protected static $rules = [
        'name' => 'required|max:255',
        'slug' => 'required|unique:authors,slug|alphadash'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    /**
     * @return void
     * @static
     */
    protected static function boot()
    {
        parent::boot();
        static::validating(function (Author $author) {
            if (!$author->slug) {
                $author->slug = strtolower(trim(str_slug($author->name), '-'));
            }
        });
    }
}

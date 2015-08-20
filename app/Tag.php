<?php

namespace Quoterr;

/**
 * Class Tag
 *
 * @package Quoterr
 * @property-read \Illuminate\Database\Eloquent\Collection|Quote[] $quotes
 * @property integer                                               $id
 * @property string                                                $name
 * @property \Carbon\Carbon                                        $created_at
 * @property \Carbon\Carbon                                        $updated_at
 * @method static \Illuminate\Database\Query\Builder|Tag whereId($value)
 * @method static \Illuminate\Database\Query\Builder|Tag whereName($value)
 * @method static \Illuminate\Database\Query\Builder|Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Tag whereUpdatedAt($value)
 */
/**
 * Class Tag
 *
 * @package Quoterr
 */
class Tag extends Model
{
    /**
     * @type string
     */
    protected $table = 'tags';

    /**
     * @type array
     */
    protected static $rules = [
        'name' => 'required',
        'slug' => 'required|alphadash|unique:tags,slug',
    ];

    protected $fillable = ['name', 'slug'];

    /**
     * @return void
     * @static
     */
    protected static function boot()
    {
        parent::boot();

        static::validating(function (Tag $tag) {
            if (!$tag->slug) {
                $tag->slug = str_slug($tag->name);
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function quotes()
    {
        return $this->belongsToMany(Quote::class);
    }
}

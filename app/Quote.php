<?php

namespace Quoterr;

use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

/**
 * Class Quote
 *
 * @package Quoterr
 * @property-read Author                                            $author
 * @property-read User                                              $poster
 * @property-read \Illuminate\Database\Eloquent\Collection|Tag[]    $tags
 * @property-read \Illuminate\Database\Eloquent\Collection|Report[] $reports
 * @property integer                                                $id
 * @property string                                                 $uuid
 * @property string                                                 $content
 * @property mixed                                                  $hash
 * @property integer                                                $author_id
 * @property integer                                                $user_id
 * @property integer                                                $likes
 * @property boolean                                                $published
 * @property string                                                 $deleted_at
 * @property \Carbon\Carbon                                         $created_at
 * @property \Carbon\Carbon                                         $updated_at
 * @method static \Illuminate\Database\Query\Builder|Quote whereId($value)
 * @method static \Illuminate\Database\Query\Builder|Quote whereUuid($value)
 * @method static \Illuminate\Database\Query\Builder|Quote whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|Quote whereAuthorId($value)
 * @method static \Illuminate\Database\Query\Builder|Quote whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Quote whereLikes($value)
 * @method static \Illuminate\Database\Query\Builder|Quote wherePublished($value)
 * @method static \Illuminate\Database\Query\Builder|Quote whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Quote whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Quote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Quote published()
 *
 */
class Quote extends Model
{
    use SoftDeletes;

    /**
     * @type string
     */
    protected $table    = 'quotes';
    protected $fillable = ['content', 'author_id', 'user_id', 'hash', 'published'];
    /**
     * @type array
     */
    protected static $rules = [
        'author_id' => 'required|exists:authors,id',
        'user_id'   => 'required|exists:users,id',
        'content'   => 'required',
        'hash'      => 'required|unique:quotes,hash',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Quote $quote) {
            if (!$quote->uuid) {
                $quote->uuid = Uuid::uuid4()->toString();
            }
        });
        static::validating(function (Quote $quote) {
            if (!$quote->hash) {
                $quote->hash = sha1($quote->content);
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function poster()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return void
     */
    public function scopePublished(Builder $query)
    {
        $query->where('published', true);
    }
}

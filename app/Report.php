<?php namespace Quoterr;

/**
 * Class Report
 *
 * @package Quoterr
 * @property-read User $reporter
 * @property-read Quote $quote
 * @property integer $id
 * @property integer $user_id
 * @property integer $quote_id
 * @property integer $rating
 * @property string $message
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|Report whereId($value)
 * @method static \Illuminate\Database\Query\Builder|Report whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Report whereQuoteId($value)
 * @method static \Illuminate\Database\Query\Builder|Report whereRating($value)
 * @method static \Illuminate\Database\Query\Builder|Report whereMessage($value)
 * @method static \Illuminate\Database\Query\Builder|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Report whereUpdatedAt($value)
 */
class Report extends Model
{
    /**
     * @type string
     */
    protected $table = 'reports';

    /**
     * @type array
     */
    protected static $rules = [
        'user_id'  => 'required|exists:users',
        'quote_id' => 'required|exists:quotes',
        'rating'   => 'required|numeric|min:1|max:5',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reporter()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quote()
    {
        return $this->belongsTo(Quote::class);
    }
}

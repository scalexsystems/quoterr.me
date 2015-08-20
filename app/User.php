<?php

namespace Quoterr;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class User
 *
 * @package Quoterr
 * @property-read \Illuminate\Database\Eloquent\Collection|Quote[]  $quotes
 * @property-read \Illuminate\Database\Eloquent\Collection|Report[] $reports
 * @property integer                                                $id
 * @property string                                                 $name
 * @property string                                                 $email
 * @property string                                                 $identifier
 * @property float                                                  $score
 * @property boolean                                                $is_admin
 * @property boolean                                                $is_moderator
 * @property string                                                 $remember_token
 * @property string                                                 $deleted_at
 * @property \Carbon\Carbon                                         $created_at
 * @property \Carbon\Carbon                                         $updated_at
 * @method static \Illuminate\Database\Query\Builder|User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|User whereIdentifier($value)
 * @method static \Illuminate\Database\Query\Builder|User whereScore($value)
 * @method static \Illuminate\Database\Query\Builder|User whereIsAdmin($value)
 * @method static \Illuminate\Database\Query\Builder|User whereIsModerator($value)
 * @method static \Illuminate\Database\Query\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|User whereUpdatedAt($value)
 */
class User extends Model implements AuthenticatableContract
{
    use Authenticatable, SoftDeletes;

    protected static $rules = [
        'name'  => 'required|max:255',
        'email' => 'email|max:255',
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'identifier'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['identifier', 'remember_token'];

    protected $casts = [
        'score' => 'double',
    ];

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}

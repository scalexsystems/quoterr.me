<?php namespace Quoterr;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * This file belongs to web.
 *
 * Author: Rahul Kadyan, <hi@znck.me>
 * Find license in root directory of this project.
 *
 */
abstract class Model extends Eloquent
{

    /**
     * Error message bag
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;
    /**
     * Validation rules
     *
     * @var Array
     */
    protected static $rules = [];
    /**
     * Custom messages
     *
     * @var Array
     */
    protected static $messages = [];
    /**
     * Validator instance
     *
     * @var \Illuminate\Validation\Validator
     */
    protected $validator;

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array    $attributes
     *
     * @param Validator $validator
     */
    public function __construct(array $attributes = [], Validator $validator = null)
    {
        parent::__construct($attributes);
        $this->validator = $validator ?: \App::make('validator');
    }

    /**
     * Listen for save event
     */
    protected static function boot()
    {
        parent::boot();
        static::saving(function (Model $model) {
            return $model->validate();
        });
    }

    /**
     * Validates current attributes against rules
     */
    public function validate()
    {
        static::fireModelEvent('validating');
        $v = $this->validator->make($this->attributes, $this->fixRules(static::$rules), static::$messages);
        if ($v->passes()) {
            return true;
        }
        $this->setErrors($v->messages());

        return false;
    }

    /**
     * Set error message bag
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * Retrieve error message bag
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Inverse of wasSaved
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }

    private function fixRules($rules)
    {
        if (!$this->exists) {
            return $rules;
        }

        foreach ($rules as $key => $rule) {
            $subRules = is_string($rule) ? explode('|', $rule) : $rule;
            foreach ($subRules as $index => $subRule) {
                if (str_contains($subRule, 'unique')) {
                    $fields = explode(',', substr($subRule, 7));
                    $table = array_shift($fields);
                    if (!count($fields)) {
                        array_push($fields, $key);
                        array_push($fields, 'NULL');
                    } elseif (count($fields) % 2 !== 0) {
                        array_push($fields, 'NULL');
                    }
                    for ($i = 0; $i < count($fields); $i += 2) {
                        if ($fields[$i + 1] !== 'NULL') {
                            $fields[$i + 1] = $this->{$fields[$i]};
                        }
                    }
                    $subRule = 'unique:' . $table . ',' . implode($fields);
                    $subRules[$index] = $subRule;

                    $rules[$key] = $subRules;
                }
            }
        }

        return $rules;
    }

    public static function validating(\Closure $callable, $priority = 0)
    {
        static::registerModelEvent('validating', $callable, $priority);
    }

    public function scopeOrderByRandom(Builder $query)
    {
        switch ($this->getConnection()->getDriverName()) {
            case 'pgsql':
            case 'sqlite':
                $query->orderBy(DB::raw('random()'));
                break;
            case 'mysql':
                $query->orderBy(DB::raw('rand()'));
                break;
        }
    }
}
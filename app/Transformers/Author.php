<?php namespace Quoterr\Transformers;

use League\Fractal\TransformerAbstract;
use Quoterr\Author as EloquentAuthor;

/**
 * This file belongs to quoterr.me.
 *
 * Author: Rahul Kadyan, <hi@znck.me>
 * Find license in root directory of this project.
 */
class Author extends TransformerAbstract
{
    public function transform(EloquentAuthor $author)
    {
        return $author->toArray();
    }
}
<?php namespace Quoterr\Transformers;

use League\Fractal\TransformerAbstract;

/**
 * This file belongs to quoterr.me.
 *
 * Author: Rahul Kadyan, <hi@znck.me>
 * Find license in root directory of this project.
 */
class Quote extends TransformerAbstract
{
    public function transform(\Quoterr\Quote $quote)
    {
        return [
            'uuid'    => $quote->uuid,
            'content' => $quote->content,
            'link'    => route('home', $quote->uuid),
            'author'  => [
                'name' => $quote->author->name,
                'link' => route('home', $quote->author->slug),
            ],
            'tags'    => $quote->tags->lists('name'),
        ];
    }
}
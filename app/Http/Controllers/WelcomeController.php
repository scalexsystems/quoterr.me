<?php

namespace Quoterr\Http\Controllers;

use Quoterr\Author;
use Quoterr\Http\Requests;
use Quoterr\Quote;
use Quoterr\Tag;
use Ramsey\Uuid\Uuid;

class WelcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param null|string $query
     *
     * @return \Quoterr\Http\Controllers\Response
     * @internal param \Illuminate\Http\Request $request
     *
     */
    public function index($query = null)
    {
        $quote = null;
        $meta = [
            'title'       => 'Quoterr',
            'description' => "Quoterr.me is an experience.",
        ];
        $api = '/api/quotes?random=1';
        if ($query !== null) {
            if (Uuid::isValid($query)) {
                $type = 'Press ‘space’ for next Quote';
                flash('Copy link from address bar to share this quote.');
                $quote = Quote::with('author')->whereUuid($query)->first();
                $meta['title'] = "A quote by {$quote->author->name} on Quoterr";
                $meta['description'] = "{$quote->content} Read awesome quotes on Quoterr.";
            } else {
                $author = Author::whereSlug($query)->first();
                if ($author) {
                    $type = "Showing quotes by {$author->name}";
                    $quote = $author->quotes()->published()->orderByRandom()->first();
                    $api = '/api/author?id=' . $query;
                    $meta['title'] = "Quotes by {$quote->author->name} on Quoterr";
                    $meta['description'] = "A fine quotation is a diamond in the hand of a man of wit, and a pebble in the hand of a fool. Read awesome quotes on Quoterr.";
                } else {
                    $tag = Tag::whereSlug($query)->first();
                    if ($tag) {
                        $type = "Quotes in {$tag->name} category";
                        $quote = $tag->quotes()->published()->orderByRandom()->first();
                        $api = '/api/tag?id=' . $query;
                    }
                }
            }
        }
        if (!$quote) {
            $type = 'Press ‘space’ for next Quote';
            $quote = Quote::with('author')->published()->orderByRandom()->first();
        }

        return view('welcome', compact('quote', 'api', 'type', 'meta'));
    }
}

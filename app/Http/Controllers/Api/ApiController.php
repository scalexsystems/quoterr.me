<?php

namespace Quoterr\Http\Controllers\Api;

use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use Quoterr\Author;
use Quoterr\Http\Controllers\Controller;
use Quoterr\Http\Requests;
use Quoterr\Quote;
use Quoterr\Tag;
use Quoterr\Transformers\Author as AuthorTransformer;
use Quoterr\Transformers\Quote as QuoteTransformer;

class ApiController extends Controller
{

    use Helpers;

    public function quotes(Request $request)
    {
        $limit = $this->getLimit($request);
        $random = $request->has('random');

        if ($random) {
            $quotes = Quote::with(['author', 'tags'])->published()->orderByRandom()->take($limit)->get();

            return $this->response()->collection($quotes, new QuoteTransformer);
        } else {
            $quotes = Quote::published()->paginate($limit);

            return $this->response()->paginator($quotes, new QuoteTransformer);
        }
    }

    public function quote(Request $request)
    {
        if ($request->has('uuid')) {
            $quote = Quote::published()->whereUuid($request->get('uuid'))->first();
        }

        if (! isset( $quote )) {
            $quote = Quote::published()->orderByRandom()->first();
        }

        return $this->response()->item($quote, new QuoteTransformer);
    }

    public function tag(Request $request)
    {
        $limit = $this->getLimit($request);
        /** @type Tag $tag */
        $tag = Tag::whereSlug($request->get('id'))->first();

        if (! $tag) {
            return $this->response()->noContent();
        }

        $quotes = $tag->quotes()->published()->orderBy('quotes.created_at', 'desc')->paginate($limit);

        return $this->response()->paginator($quotes, new QuoteTransformer);
    }

    public function author(Request $request)
    {
        $limit = $this->getLimit($request);
        $author_id = $request->get('id', 0);
        /** @type Author $author */
        $author = Author::whereId((int) $author_id)->orWhere('slug', $author_id)->first();
        if (! $author) {
            return $this->response()->noContent();
        }
        $quotes = $author->quotes()->published()->orderBy('quotes.created_at', 'desc')->paginate($limit);

        return $this->response()->paginator($quotes, new QuoteTransformer);
    }

    public function authors(Request $request)
    {
        $list = $request->has('list');
        $limit = $this->getLimit($request);
        if ($list) {
            $authors = Author::orderBy('name')->lists('name');

            return $this->response()->item($authors, function ($authors) {
                return $authors;
            });
        }

        $authors = Author::orderBy('name')->paginate($limit);

        return $this->response()->paginator($authors, new AuthorTransformer);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    protected function getLimit(Request $request)
    {
        $limit = min($request->get('limit', 50), 50);

        return $limit;
    }
}

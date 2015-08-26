<?php

namespace Quoterr\Http\Controllers;

use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use Quoterr\Author;
use Quoterr\Http\Requests;
use Quoterr\Quote;
use Quoterr\Tag;

class QuoteController extends Controller
{
    /**
     * QuoteController constructor.
     *
     * @param \Illuminate\Auth\Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $user = $auth->user();

        \View::share('user', $user);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Quoterr\Http\Controllers\Response
     */
    public function index(Request $request)
    {
        $q = $request->get('q');
        $tag = str_slug($q);
        $authors = Author::where('name', 'ilike', "%{$q}%")->take(20)->get();
        $tags = Tag::where('name', 'ilike', "%{$tag}%")->take(20)->get();
        $query = \DB::table(\DB::raw("quotes, to_tsvector(quotes.content) target, to_tsquery('english', ?) query"))
            ->select(['quotes.*', \DB::raw('ts_rank_cd(target, query) as rank')])
            ->setBindings([str_replace(' ', '|', $q)]);
        $quotes = Quote::query()->setQuery($query)
            ->published()->whereRaw(\DB::raw('target @@ query'))
            ->orderBy('rank', 'desc')
            ->paginate(50);
        foreach ($quotes as $quote) {
            $quote->content = $this->highlight($quote->content, $q);
        }

        $quotes->load(['author']);
        $quotes->appends(\Input::except('page'));


        return view('quote.index', compact('authors', 'tags', 'quotes', 'q'));
    }

    protected function highlight($text, $words)
    {
        preg_match_all('~\w+~', $words, $m);
        if (!$m) {
            return $text;
        }
        $re = '/\\b(' . implode('|', $m[0]) . ')\\b/i';

        return preg_replace($re, '<span class="highlight">$0</span>', $text);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $quote = new Quote;

        return view('quote.create', compact('quote'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int     $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}

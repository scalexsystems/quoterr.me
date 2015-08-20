<?php

namespace Quoterr\Http\Controllers;

use Illuminate\Http\Request;

use Quoterr\Http\Requests;
use Quoterr\Http\Controllers\Controller;
use Quoterr\Tag;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('tag.index', ['tags' => Tag::orderBy('name')->paginate(100) ]);
    }
}

@extends('app')

@section('content')
    <br>
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="quoterr-well">
                    {!! Form::open(['route' => 'quote.index', 'method' => 'get']) !!}
                    <div class="form-group" style="margin-bottom: 0">
                        <div class="input-group">
                            <input type="text" class="form-control" name="q" placeholder="search any quotes, authors or categories" value="{{ $q }}">
                    <span class="input-group-btn">
                        <input type="submit" class="btn btn-primary" value="search">
                    </span>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="container">
        <div class="row">
            @if(count($tags))
                <div class="col-xs-12 col-sm-6">
                    <div class="panel panel-light">
                        <div class="panel-heading">
                            Categories <div class="pull-right">
                                <a href="{{ route('tag.index') }}" class="text-link">view all</a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    @foreach($tags as $tag)
                                        <div class="panel-link">
                                            <a class="text-link" href="{{ route('home', [$tag->slug]) }}">{{ $tag->name }}</a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(count($authors))
            <div class="col-xs-12 col-sm-6">
                <div class="panel panel-light">
                    <div class="panel-heading">
                        Authors <div class="pull-right">
                            <a href="#" class="text-link">view all</a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12">
                                @foreach($authors as $author)
                                    <div class="panel-link">
                                        <a class="text-link" href="{{ route('home', [$author->slug]) }}">{{ $author->name }}</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    <br>
    @if(count($quotes))
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    @foreach($quotes as $quote)
                        <div class="quoterr-well">
                            <blockquote class="quote" cite="">
                                <div style="width: 100%">
                                    <p v-html="quote.content">{!!  $quote->content !!}</p>
                                    <footer class="text-right"><a href="{{ route('home', $quote->author->slug) }}" class="text-link" v-text="quote.author.name">{{ $quote->author->name }}</a></footer>
                                </div>
                            </blockquote>
                        </div>
                        <br>
                    @endforeach
                </div>
                <div class="col-xs-12 text-center">
                    {!! $quotes->render() !!}
                </div>
            </div>
        </div>
    @endif
@endsection
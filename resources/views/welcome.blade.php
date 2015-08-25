@extends('app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/nprogress.css') }}">
@endsection


@section('header')
@endsection
@section('footer')
@endsection

@section('content')
    <div class="page" id="vue-arena" v-on="keyup: next | key 'space'">
        <div class="quote-help">
            <div role="alert" class="alert text-white" style="margin-bottom: 0">
                {{ $type }}
                &nbsp;
                <a href="{{ route('home') }}" aria-label="Close" data-dismiss="alert">&times;</a>
            </div>
        </div>
        <div class="quote-arena">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                        <blockquote cite="">
                            <div style="width: 100%">
                                <p v-html="quote.content">{{ $quote->content }}</p>
                                <footer class="text-right"><a href="{{ route('home', $quote->author->slug) }}" class="text-link" v-text="quote.author.name">{{ $quote->author->name }}</a></footer>
                            </div>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>

        <div class="quote-menu text-right">
            <div class="pull-left">
                <img src="{{ asset('img/logo-no-shadow.svg') }}" alt="Quoterr">
            </div>
            <div id="collapse" style="display: inline-block; overflow: hidden; white-space: nowrap">
                <a href="#" class="btn-round btn-menu collapsed" type="button" id="menu-toggle" aria-expanded="false" aria-controls="collapseExample" title="Toggle Menu">
                    <i class="fa fa-bars bars"></i>
                    <i class="fa fa-times times"></i>
                </a>
                <a href="#" data-toggle="tooltip" title="Share it" class="btn-round" role="button" v-attr="href: quote.link">
                    <i class="fa fa-share-alt"></i>
                </a>
                <a href="#" class="btn-round" data-toggle="tooltip" title="Slideshow" role="button" v-on="click: slideshow($event)">
                    <i class="fa fa-play"></i>
                </a>
                <a href="#" class="btn-round hidden-xs" role="button" data-toggle="tooltip" title="Fullscreen" id="screen-full">
                    <i class="fa fa-expand"></i>
                </a>
                <span data-toggle="modal" data-target="#add-modal">
                    <a href="#" class="btn-round hidden-xs" data-toggle="tooltip" title="Add a quote" role="button">
                        <i class="fa fa-plus"></i>
                    </a>
                </span>
                <span data-toggle="modal" data-target="#search-modal">
                    <a href="#" class="btn-round" data-toggle="tooltip" title="Search" role="button">
                        <i class="fa fa-search"></i>
                    </a>
                </span>
                @if(Auth::check())
                    <a href="{{ route('logout') }}" class="btn-round" role="button" data-toggle="tooltip" title="Logout">
                        <i class="fa fa-sign-out"></i>
                    </a>
                @else
                    <span data-toggle="modal" data-target="#add-modal">
                        <a href="#" class="btn-round" role="button" data-toggle="tooltip" title="Login">
                            <i class="fa fa-sign-in"></i>
                        </a>
                    </span>
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade" id="login-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Login</h4>
                </div>
                <div class="modal-body">
                    <div class="login text-center">
                        <p>
                            Login with Facebook or Twitter.
                        </p><br>
                        <div>
                            <div class="btn-group">
                                <a role="button" class="btn btn-primary"><i class="fa fa-facebook"  style="width: 16px"></i></a>
                                <a href="{{ route('login', 'facebook') }}" class="btn btn-primary social">
                                    Login with Facebook
                                </a>
                            </div>
                        </div>
                        <br>
                        <div>
                            <div class="btn-group">
                                <a role="button" class="btn btn-primary"><i class="fa fa-twitter" style="width: 16px"></i></a>
                                <a href="{{ route('login', 'twitter') }}" class="btn btn-primary social">
                                    Login with Twitter
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="add-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add new quotes</h4>
                </div>
                <div class="modal-body">
                    <div class="login text-center">
                        <h2>
                            Coming soon.
                        </h2><br>
                        <footer>
                            <small>
                                If you are a developer, then you help us out at <br>
                                <a href="https://github.com/the-quoterr/quoterr.me">https://github.com/the-quoterr/quoterr.me</a>
                            </small>
                        </footer>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <div class="modal fade" id="search-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Find quotes</h4>
                </div>
                <div class="modal-body">
                    <div class="login text-center">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="quoterr-well">
                                    {!! Form::open(['route' => 'quote.index', 'method' => 'get']) !!}
                                    <div class="form-group" style="margin-bottom: 0">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="q" placeholder="search any quotes, authors or categories">
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
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection

@section('body.js')
    <script src="{{ asset('js/screenfull.js') }}"></script>
    <script src="{{ asset('js/nprogress.js') }}"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/vue/0.12.10/vue.min.js"></script>
    <script>
        NProgress.configure({ showSpinner: false });
        var v = new Vue({
            el: '#vue-arena',
            data: {
                quote: {
                    content: '{{ $quote->content }}',
                    author: {
                        name: '{{ $quote->author->name }}',
                    },
                    link: '{{ route('home', ['quote' => $quote->uuid]) }}',
                },
                quotes: [],
                slideshowTimer: -1,
                index: 0
            },
            created: function() {
                this.getMore();
            },
            methods: {
                next: function() {
                    this.quote = this.quotes[this.index];
                    this.index = (++this.index) % this.quotes.length;
                    if (this.index + 4 >= this.quotes.length) {
                        this.getMore();
                    }
                },
                slideshow: function(e) {
                    e.preventDefault();
                    var self = this;
                    if (self.slideshowTimer == -1) {
                        NProgress.start();
                        window.setInterval(function(){NProgress.inc(0.05);}, 500)
                        self.slideshowTimer = window.setInterval(function() {
                            NProgress.done(true);
                            self.next();
                            NProgress.start();
                        }, 10000);
                    } else {
                        window.clearTimeout(self.slideshowTimer);
                        self.slideshowTimer = -1;
                        NProgress.done(true);
                    }
                },
                getMore: function() {
                    var self = this;
                    $.ajax('{{ $api }}', {
                        headers: {
                            Accept: 'application/vnd.quoterr.v1+json'
                        },
                        method: 'GET',
                        success: function(response) {
                            Array.prototype.push.apply(self.quotes, response.data);
                        }
                    })
                }
            }
        });
        function debouncer( func , timeout ) {
            var timeoutID , timeout = timeout || 200;
            return function () {
                var scope = this , args = arguments;
                clearTimeout( timeoutID );
                timeoutID = setTimeout( function () {
                    func.apply( scope , Array.prototype.slice.call( args ) );
                } , timeout );
            }
        }

        $(document).ready(function(){
            $('[data-toggle=tooltip]').tooltip({placement: 'bottom'});
            var setBack = function() {
                var $page = $('.page'),
                        width = $page.width(),
                        height = $page.height();
                $page.css('background-image', 'url(https://unsplash.it/' + width + '/' + height + '?random)');
            };

            setBack();

            var $toggle = $('#menu-toggle'),
                    $menu = $('#collapse'),
                    width = $menu.width(),
                    collapsed = true;

            $menu.width(48);

            $(window).on('resize', debouncer(setBack, 400));

            $(window).keypress(function(e){
                if (e.which == 32)
                {
                    v.next();
                }
            });
            $toggle.on('click', function(e) {
                e.preventDefault();

                $toggle.toggleClass('collapsed');
                if (collapsed) {
                    $menu.animate({width: width+'px'});
                } else {
                    $menu.animate({width: 48+'px'});
                }

                collapsed = !collapsed;
            });

            $('#screen-full').on('click', function(e){
                e.preventDefault();

                screenfull.toggle();
            });
        });
    </script>
@endsection
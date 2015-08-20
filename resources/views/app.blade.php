<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quoterr</title>

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    @yield('meta')

    <link rel="author" href={{ url('humans.txt') }}>
    <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    @yield('css')

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
@if (session('flash_notification'))
    <div class="row">
        <div class="col-xs-12">
            @include('flash::notifications')
        </div>
    </div>
@endif

@yield('content')
<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/bootstrap.js') }}"></script>
@yield('js')
<script src="{{ elixir('js/app.js') }}"></script>
@yield('body.js')
</body>
</html>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Landmark Network</title>

    <link href="{{ masset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ masset('font-awesome/css/font-awesome.css') }}" rel="stylesheet">

    <link href="{{ masset('css/animate.css') }}" rel="stylesheet">
    <link href="{{ masset('css/style.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ masset('favicon.ico')  }}" type="image/x-icon" />
    @stack('heads')
    {!! Asset::styles() !!}

</head>

<body class="gray-bg">

    <div class="loginColumns animated fadeInDown">
        @yield('content')
        <hr/>
        <div class="row">
            <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3  col-sm-6 col-sm-offset-3">
              Landscape &copy; {{ date('Y') }}
            </div>
        </div>
    </div>

</body>

</html>

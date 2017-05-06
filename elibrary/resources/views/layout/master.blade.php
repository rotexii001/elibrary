<!DOCTYPE html>
<html @yield('html_class','') lang="en">
    <head>
<!-- FOR NON-AUTHENTICATED USERS-->  
@section('headerfile_unauth')
    
    <title>@yield('title', '')</title>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Olaosebikan Rotimi : olaosebeikanrotimi@gmail.com">

    <link href="{{URL::asset('assets/css/woff.css')}}" rel="stylesheet">

    <!-- Roboto Web Font -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en" rel="stylesheet">

    <!-- App CSS -->
    <link type="text/css" href="{{URL::asset('assets/css/style.min.css')}}" rel="stylesheet">

    @show  

@section('pageLevelCss')
@show

</head>

    <body @yield('bodyClass','')>

        
        @section('contentData')
        @show

       <!-- jQuery -->
        <script src="{{URL::asset('assets/vendor/jquery.min.js')}}"></script>

        <!-- Bootstrap -->
        <script src="{{URL::asset('assets/vendor/tether.min.js')}}"></script>
        <script src="{{URL::asset('assets/vendor/bootstrap.min.js')}}"></script>

        <!-- AdminPlus -->
        <script src="{{URL::asset('assets/vendor/adminplus.js')}}"></script>

        <!-- App JS -->
        <script src="{{URL::asset('assets/js/main.min.js')}}"></script>

        @section('pageLevelJs')
        @show
    </body>
</html>
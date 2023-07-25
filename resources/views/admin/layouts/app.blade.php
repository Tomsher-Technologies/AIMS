<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ env('APP_NAME') }} - @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="color-scheme" content="only light">
    <link rel="stylesheet" href="{{ asset('assets/font/iconsmind-s/css/iconsminds.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/font/simple-line-icons/css/simple-line-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.rtl.only.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-float-label.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @yield('header')
    <script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>
    <link rel="shortcut icon" href="img/favicon.ico" />
</head>
<body id="app-container" class="menu-default show-spinner">
    @include('admin.includes.sidebar')
    @include('admin.includes.header')
    <main>
        @yield('content')
    </main>
    <footer class="page-footer">
        @include('admin.includes.footer')
    </footer>
    
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/dore.script.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    @yield('footer')
</body>
</html>
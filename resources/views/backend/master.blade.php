<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    @include('backend.layouts.style')
</head>

<body>
    <div id="app">
        @include('backend.layouts.sidebar')

        <div id="main" class='layout-navbar'>
            @include('backend.layouts.header')

            <div id="main-content">

                @yield('content')

                @include('backend.layouts.footer')
            </div>
        </div>
    </div>


    @include('backend.layouts.script')
</body>

</html>

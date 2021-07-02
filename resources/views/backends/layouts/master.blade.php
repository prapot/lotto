<!DOCTYPE html>
<html lang="en">
<head>
  <title>{{ config('app.name') }}</title>
  <meta charset="UTF-8" />
  <!-- <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
  <link rel="alternate icon" href="{{ asset('images/favicon.png') }}"> -->
  
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Styles -->
  @yield('css')
  <link href="{{ asset(mix('backend/css/app.css')) }}" rel="stylesheet">
</head>
<body class="c-app">
    @include('backends.layouts.sidebar')
    <div class="c-wrapper">
        @include('backends.layouts.header')
        @yield('content')
        @include('backends.layouts.footer')
    </div>
</body>
  <script>
    function chkNumber(ele)
    {
      var vchar = String.fromCharCode(event.keyCode);
      if ((vchar<'0' || vchar>'9')) return false;
      ele.onKeyPress=vchar;
    }
  </script>
  <script type="text/javascript">var baseUrl = '{{ @url('/') }}' </script>
 
  @yield('js')
  <script src="{{ asset(mix('backend/js/app.js')) }}" defer></script>
</html>
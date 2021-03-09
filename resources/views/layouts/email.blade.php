<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @yield('custom_css')

    <title>{{env('APP_NAME')}}</title>
</head>
<body>
    
    @yield('content')
    
    <footer>
        Best regards
        <br>
        OAS Team
    </footer>
    @yield('custom_js')
</body>
</html>
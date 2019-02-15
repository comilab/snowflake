<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link href="//fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons" rel="stylesheet">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>snowflake</title>
        @if (env('APP_ENV') === 'production')
            <link href="/static/css/app.css" rel="stylesheet">
        @endif
    </head>
    <body>
        <div id="app"></div>
        @if (env('APP_ENV') === 'production')
            <script type="text/javascript" src="/static/js/manifest.js"></script>
            <script type="text/javascript" src="/static/js/vendor.js"></script>
            <script type="text/javascript" src="/static/js/app.js"></script>
        @else
            <script src="//localhost:8080/app.js"></script>
        @endif
    </body>
</html>

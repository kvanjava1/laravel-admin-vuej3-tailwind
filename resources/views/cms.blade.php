<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite([
            'resources/css/cms/app.css', 
            'resources/js/cms/app.ts'
        ])
    </head>
    <body id="app">
    </body>
    @routes
</html>

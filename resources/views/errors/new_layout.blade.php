<!doctype html>
<html lang="ru">
    <head>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="{{ mix('css/app.css') }}">
            <title>@yield('title') :(</title>
    </head>
    <body>
        <div class="container container-fluid" style="width: 384px;">
            <div class="alert alert-danger" role="alert">
                <p>На сайте возникла ошибка :(</p>
                <p><strong>Код: @yield('code').</strong> @yield('message')</p>
                <p><u><a class="alert-link" href="{{ route('root') }}">Вернуться на главную</a></u></p>
            </div>
        </div>
    </body>
</html>

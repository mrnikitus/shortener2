<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') – Shortener2</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <style>
        body {
            padding-top: 20px;
            padding-bottom: 20px;
        }
    </style>
</head>
<body style="position: relative; display: flex; align-content: center; justify-content: center;">
@yield('content')
<script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
</body>
</html>

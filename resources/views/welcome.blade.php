<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        }
    </script>
    <link rel="stylesheet" href="{{ asset('jquery-ui/jquery.ui.css') }}">
    <title>Events Calendar</title>
</head>

<body>
    <div id="app">
        <div class="container-fluid">
            <Calendar></Calendar>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
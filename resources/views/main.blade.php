<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no, minimum-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laravel</title>

    @vite('resources/js/app.js')
    <style>
        html {
            height: 100vh;
        }

    </style>

</head>
<body ontouchmove="event.preventDefault()">
<div id="app"></div>
</body>
<script>
    window.addEventListener("wheel", e => {
        if (e.ctrlKey)
            e.preventDefault();//prevent zoom
    }, {passive: false});
</script>
</html>

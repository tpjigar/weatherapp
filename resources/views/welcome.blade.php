<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=devioce-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/css/main.css">

    {{-- Algolia script --}}
    <script src="https://cdn.jsdelivr.net/npm/places.js@1.18.1"></script>

    <script src="https://rawgithub.com/darkskyapp/skycons/master/skycons.js" defer></script>
    <script src="/js/app.js" defer></script>
</head>
<body class="bg-blue-200">
<input type="search" id="address" class="form-control" placeholder="Where are we going?" />

<p>Selected: <strong id="address-value">none</strong></p>
    <div id="app" class="flex justify-center pt-16">
        <weather-app></weather-app>
    </div>

</body>
</html>

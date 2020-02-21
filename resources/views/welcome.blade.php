<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=devioce-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/css/main.css">

    {{-- Algolia css for map only    --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/leaflet/1/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/leaflet/1/leaflet.js"></script>

    {{-- Algolia script --}}
    <script src="https://cdn.jsdelivr.net/npm/places.js@1.18.1" defer></script>

    <script src="https://rawgithub.com/darkskyapp/skycons/master/skycons.js" defer></script>
    <script src="/js/app.js" defer></script>
</head>
<body class="bg-blue-200">
    <div id="app" class="flex justify-center pt-16">
        <weather-app></weather-app>
    </div>

</body>
</html>

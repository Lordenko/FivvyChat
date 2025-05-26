<!DOCTYPE html>
<html class="h-full bg-white">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta >
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    @inertiaHead
    @routes
  </head>
  <body class="h-full">
    @inertia
  </body>
</html>

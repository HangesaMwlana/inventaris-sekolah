<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Inventaris Sekolah') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex items-center justify-center 
             bg-white-600 font-sans antialiased">

    <div class="w-full max-w-md">

        <!-- Logo / Title -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-black tracking-wide">
                Inventaris Sekolah
            </h1>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-1xl shadow-xl px-8 py-8">

            {{ $slot }}

        </div>

    </div>

</body>
</html>

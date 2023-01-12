<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') }} &dash; @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-50 antialiased text-gray-700">

    @include('header')

    <main class="md:p-8 max-w-screen-xl p-4 mx-auto">

        <x-flash-message />

        @yield('content')

    </main>

    @include('footer')

    @livewireScripts
</body>

</html>

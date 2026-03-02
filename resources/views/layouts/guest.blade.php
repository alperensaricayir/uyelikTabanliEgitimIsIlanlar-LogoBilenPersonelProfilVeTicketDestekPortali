<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>

<body class="font-sans text-gray-900 dark:text-gray-100 antialiased transition-colors duration-200 relative">

    {{-- Dark Mode Toggle --}}
    <div class="absolute top-4 right-4 sm:top-6 sm:right-8 z-50">
        <button type="button"
            onclick="document.documentElement.classList.toggle('dark'); localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light'"
            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium bg-white hover:bg-gray-100 text-gray-900 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-100 transition-colors shadow border border-gray-200 dark:border-gray-700">
            <span class="block dark:hidden">🌙 Dark</span>
            <span class="hidden dark:block">☀️ Light</span>
        </button>
    </div>

    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-950 transition-colors duration-200">
        <div>
            <a href="/">
                <img src="{{ asset('images/3sgruplogoyeni.png') }}" alt="3S Grup logo"
                    class="h-28 sm:h-32 md:h-40 lg:h-48 w-auto mx-auto pb-6 drop-shadow-md dark:drop-shadow-lg object-contain"
                    onerror="this.outerHTML='<span class=\'text-5xl font-bold font-serif text-gray-900 dark:text-white mx-auto pb-6 block text-center\'>3S Grup</span>'">
            </a>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-900 border dark:border-gray-800 shadow-md overflow-hidden sm:rounded-lg transition-colors duration-200">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
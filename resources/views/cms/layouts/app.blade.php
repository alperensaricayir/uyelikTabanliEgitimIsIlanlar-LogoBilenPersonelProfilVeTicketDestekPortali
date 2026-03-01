<!DOCTYPE html>
<html lang="tr" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CMS Panel – @yield('title', 'Yönetim')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- CMS-scoped accessibility overrides (does not affect public site) --}}
    <link rel="stylesheet" href="{{ asset('css/cms-admin.css') }}">
</head>

<body class="h-full">

    <div class="cms-layout flex h-full">
        {{-- Sidebar --}}
        @include('cms.partials.sidebar')

        {{-- Main --}}
        <div class="flex-1 flex flex-col overflow-auto">
            {{-- Top bar --}}
            <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between flex-shrink-0">
                <h1 class="text-lg font-bold text-slate-800">@yield('title', 'CMS')</h1>
                <div class="flex items-center gap-3">
                    <span class="cms-username text-sm">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="text-xs font-semibold text-red-600 hover:text-red-800 transition-colors">Çıkış</button>
                    </form>
                </div>
            </header>

            {{-- Flash messages --}}
            @if(session('success'))
                <div
                    class="mx-6 mt-4 p-3 bg-green-50 border border-green-200 rounded-lg flex items-center gap-2 cms-flash-success">
                    <svg class="w-4 h-4 flex-shrink-0 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mx-6 mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm font-medium text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Page content --}}
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>

</body>

</html>
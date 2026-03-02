<x-app-layout>

    {{-- ──────────────────────────────────────────────────
    DASHBOARD – Udemy-style light theme
    Variables from DashboardController:
    $upcomingTrainingsCount, $openTicketsCount,
    $newNotificationsCount, $profileCompleteness,
    $upcomingTrainings, $openTickets
    ──────────────────────────────────────────────────── --}}

    <div class="min-h-screen bg-neutral-50 dark:bg-gray-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

            {{-- ── WELCOME BANNER ─────────────────────────── --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-neutral-900 dark:text-gray-100">
                        Hoş geldin, {{ auth()->user()->name }}! 👋
                    </h1>
                    <p class="text-sm text-neutral-500 dark:text-gray-400 mt-0.5">
                        Bugün ne öğrenmek istersin?
                    </p>
                </div>

                {{-- Premium upgrade CTA --}}
                @if(!auth()->user()->isPremium())
                    <a href="{{ route('premium.services') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-semibold shadow-sm hover:shadow-md hover:from-amber-600 hover:to-orange-600 transition-all">
                        ⭐ Premium'a Geç
                    </a>
                @else
                    <span
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-100 text-amber-800 text-sm font-semibold">
                        ⭐ Premium Üye
                    </span>
                @endif
            </div>

            {{-- ── KPI STATS GRID ──────────────────────────── --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- Upcoming Trainings --}}
                <a href="{{ route('trainings.index') }}"
                    class="group bg-white dark:bg-gray-900 rounded-2xl border border-neutral-200 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4 hover:shadow-md hover:border-violet-200 dark:hover:border-violet-600 transition-all duration-200">
                    <div
                        class="w-12 h-12 rounded-xl bg-violet-100 dark:bg-violet-900/40 flex items-center justify-center flex-shrink-0 group-hover:bg-violet-200 dark:group-hover:bg-violet-900/60 transition-colors">
                        <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-2xl font-bold text-neutral-900 dark:text-gray-100">{{ $upcomingTrainingsCount }}
                        </p>
                        <p class="text-xs font-medium text-neutral-500 dark:text-gray-400 leading-tight mt-0.5">Yaklaşan
                            Eğitim<br><span class="text-neutral-400 dark:text-gray-500 font-normal">Sonraki 7 gün</span>
                        </p>
                    </div>
                </a>

                {{-- Open Tickets --}}
                <a href="{{ route('tickets.index') }}"
                    class="group bg-white dark:bg-gray-900 rounded-2xl border border-neutral-200 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4 hover:shadow-md hover:border-emerald-200 dark:hover:border-emerald-600 transition-all duration-200">
                    <div
                        class="w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center flex-shrink-0 group-hover:bg-emerald-200 dark:group-hover:bg-emerald-900/60 transition-colors">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-2xl font-bold text-neutral-900 dark:text-gray-100">{{ $openTicketsCount }}</p>
                        <p class="text-xs font-medium text-neutral-500 dark:text-gray-400 leading-tight mt-0.5">Açık
                            Destek
                            Ticket'ı<br><span class="text-neutral-400 dark:text-gray-500 font-normal">Yanıt
                                bekleyen</span></p>
                    </div>
                </a>

                {{-- Notifications --}}
                <div
                    class="bg-white dark:bg-gray-900 rounded-2xl border border-neutral-200 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-rose-100 dark:bg-rose-900/40 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-rose-500 dark:text-rose-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-2xl font-bold text-neutral-900 dark:text-gray-100">{{ $newNotificationsCount }}
                        </p>
                        <p class="text-xs font-medium text-neutral-500 dark:text-gray-400 leading-tight mt-0.5">
                            Bildirim<br><span class="text-neutral-400 dark:text-gray-500 font-normal">Okunmamış</span>
                        </p>
                    </div>
                </div>

                {{-- Profile Completeness --}}
                <a href="{{ route('profile.edit') }}"
                    class="group bg-white dark:bg-gray-900 rounded-2xl border border-neutral-200 dark:border-gray-700 shadow-sm p-5 hover:shadow-md hover:border-amber-200 dark:hover:border-amber-600 transition-all duration-200">
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center flex-shrink-0 group-hover:bg-amber-200 dark:group-hover:bg-amber-900/60 transition-colors">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xl font-bold text-neutral-900 dark:text-gray-100">{{ $profileCompleteness }}%
                            </p>
                            <p class="text-xs text-neutral-500 dark:text-gray-400">Profil</p>
                        </div>
                    </div>
                    <div class="w-full bg-neutral-200 rounded-full h-1.5">
                        <div class="bg-amber-500 rounded-full h-1.5 transition-all duration-500"
                            style="width: {{ $profileCompleteness }}%"></div>
                    </div>
                    @if($profileCompleteness < 100)
                        <p class="text-xs text-amber-600 mt-1.5 font-medium group-hover:underline">Tamamla →</p>
                    @endif
                </a>
            </div>

            {{-- ── MAIN 2-COL GRID ─────────────────────────── --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

                {{-- Upcoming Trainings (3/5) --}}
                <div
                    class="lg:col-span-3 bg-white dark:bg-gray-900 rounded-2xl border border-neutral-200 dark:border-gray-700 shadow-sm overflow-hidden">

                    {{-- Section header --}}
                    <div
                        class="flex items-center justify-between px-6 py-4 border-b border-neutral-100 dark:border-gray-800">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-6 h-6 rounded-md bg-violet-100 dark:bg-violet-900/40 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-violet-600 dark:text-violet-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h2 class="font-semibold text-neutral-900 dark:text-gray-100 text-sm">Yaklaşan Eğitimler
                            </h2>
                        </div>
                        <a href="{{ route('trainings.index') }}"
                            class="text-xs font-semibold text-violet-600 dark:text-violet-400 hover:text-violet-800 dark:hover:text-violet-300 transition-colors">
                            Tümünü gör →
                        </a>
                    </div>

                    @if($upcomingTrainings->isEmpty())
                        {{-- Empty state --}}
                        <div class="py-14 px-6 text-center">
                            <div
                                class="w-14 h-14 rounded-full bg-neutral-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-7 h-7 text-neutral-400 dark:text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="font-semibold text-neutral-700 dark:text-gray-200 text-sm">Yaklaşan eğitim bulunmuyor.
                            </p>
                            <p class="text-neutral-500 dark:text-gray-400 text-xs mt-1">Yeni eğitimler için keşfet bölümüne
                                göz atın.</p>
                            <a href="{{ route('trainings.index') }}"
                                class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-violet-600 text-white text-xs font-semibold hover:bg-violet-700 transition-colors">
                                Eğitimlere Bak
                            </a>
                        </div>
                    @else
                        <ul class="divide-y divide-neutral-100 dark:divide-gray-800">
                            @foreach($upcomingTrainings as $training)
                                <li
                                    class="px-6 py-4 flex items-center gap-4 hover:bg-neutral-50 dark:hover:bg-gray-800/50 transition-colors">

                                    {{-- Calendar mini widget --}}
                                    <div
                                        class="flex-shrink-0 w-11 h-11 rounded-xl bg-violet-50 dark:bg-violet-900/20 border border-violet-100 dark:border-violet-800 flex flex-col items-center justify-center text-violet-700 dark:text-violet-400">
                                        @if($training->published_at)
                                            <span class="text-[9px] font-bold uppercase leading-none">
                                                {{ $training->published_at->format('M') }}
                                            </span>
                                            <span class="text-base font-bold leading-none mt-0.5">
                                                {{ $training->published_at->format('d') }}
                                            </span>
                                        @else
                                            <span class="text-[9px] font-bold uppercase leading-none">--</span>
                                            <span class="text-base font-bold leading-none mt-0.5">--</span>
                                        @endif
                                    </div>

                                    {{-- Info --}}
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-neutral-800 dark:text-gray-100 truncate">
                                            {{ $training->title }}
                                        </p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-xs text-neutral-500 dark:text-gray-400">
                                                {{ $training->published_at ? $training->published_at->format('H:i') : 'Tarih Yok' }}
                                            </span>
                                            @if($training->is_premium_only)
                                                <span
                                                    class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-amber-100 text-amber-700">
                                                    Premium
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- CTA --}}
                                    <a href="{{ route('trainings.show', $training) }}"
                                        class="flex-shrink-0 px-3 py-1.5 rounded-lg border border-violet-200 dark:border-violet-700 text-violet-600 dark:text-violet-400 text-xs font-semibold hover:bg-violet-50 dark:hover:bg-violet-900/30 transition-colors">
                                        Detay
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                {{-- Support Tickets (2/5) --}}
                <div
                    class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-2xl border border-neutral-200 dark:border-gray-700 shadow-sm overflow-hidden flex flex-col">

                    <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-100 dark:border-gray-800">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-md bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            </div>
                            <h2 class="font-semibold text-neutral-900 dark:text-gray-100 text-sm">Destek Ticket'larım</h2>
                        </div>
                        <a href="{{ route('tickets.index') }}"
                            class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 transition-colors">
                            Tümü →
                        </a>
                    </div>

                    @if($openTickets->isEmpty())
                        <div class="flex-1 py-10 px-6 text-center flex flex-col items-center justify-center">
                            <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-emerald-400 dark:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-neutral-700 dark:text-gray-200">Her şey yolunda!</p>
                            <p class="text-xs text-neutral-500 dark:text-gray-400 mt-1">Açık ticket'ınız yok.</p>
                            <a href="{{ route('tickets.create') }}"
                                class="mt-4 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-emerald-200 dark:border-emerald-700 text-emerald-700 dark:text-emerald-400 text-xs font-semibold hover:bg-emerald-50 dark:hover:bg-emerald-900/30 transition-colors">
                                + Destek Ticket'ı Oluştur
                            </a>
                        </div>
                    @else
                        <ul class="flex-1 divide-y divide-neutral-100 dark:divide-gray-800">
                            @foreach($openTickets as $ticket)
                                <li class="px-6 py-3.5 hover:bg-neutral-50 dark:hover:bg-gray-800/50 transition-colors">
                                    <div class="flex items-start gap-3">
                                        <span
                                            class="mt-0.5 inline-flex px-2 py-0.5 rounded text-[10px] font-bold {{ $ticket->statusBadgeClass() }} flex-shrink-0">
                                            {{ $ticket->statusLabel() }}
                                        </span>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-neutral-800 dark:text-gray-100 truncate">{{ $ticket->subject }}
                                            </p>
                                            <p class="text-xs text-neutral-500 dark:text-gray-400 mt-0.5">
                                                {{ $ticket->updated_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <a href="{{ route('tickets.show', $ticket) }}"
                                            class="flex-shrink-0 text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 mt-1 transition-colors">
                                            Gör
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="px-6 py-3 bg-neutral-50 dark:bg-gray-800/30 border-t border-neutral-100 dark:border-gray-800">
                            <a href="{{ route('tickets.create') }}"
                                class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 transition-colors">
                                + Destek Ticket'ı Oluştur
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── QUICK ACTIONS ───────────────────────────── --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-neutral-200 dark:border-gray-700 shadow-sm p-6">
                <h2 class="font-semibold text-neutral-900 dark:text-gray-100 mb-4 text-sm">Hızlı Erişim</h2>

                <div class="grid grid-cols-3 sm:grid-cols-6 gap-3">

                    {{-- Trainings --}}
                    <a href="{{ route('trainings.index') }}"
                        class="group flex flex-col items-center gap-2 py-5 px-3 rounded-xl bg-violet-50 dark:bg-violet-900/10 hover:bg-violet-100 dark:hover:bg-violet-900/30 border border-violet-100 dark:border-violet-800/50 hover:border-violet-200 dark:hover:border-violet-700 transition-all duration-150">
                        <div
                            class="w-10 h-10 rounded-xl bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center group-hover:shadow transition-shadow">
                            <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-violet-700 dark:text-violet-400 text-center leading-tight">Eğitimler</span>
                    </a>

                    {{-- Support --}}
                    <a href="{{ route('tickets.create') }}"
                        class="group flex flex-col items-center gap-2 py-5 px-3 rounded-xl bg-emerald-50 dark:bg-emerald-900/10 hover:bg-emerald-100 dark:hover:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800/50 hover:border-emerald-200 dark:hover:border-emerald-700 transition-all duration-150">
                        <div
                            class="w-10 h-10 rounded-xl bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center group-hover:shadow transition-shadow">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 text-center leading-tight">Destek
                            Ticket'ı</span>
                    </a>

                    {{-- Jobs --}}
                    <a href="{{ route('jobs.index') }}"
                        class="group flex flex-col items-center gap-2 py-5 px-3 rounded-xl bg-sky-50 dark:bg-sky-900/10 hover:bg-sky-100 dark:hover:bg-sky-900/30 border border-sky-100 dark:border-sky-800/50 hover:border-sky-200 dark:hover:border-sky-700 transition-all duration-150">
                        <div
                            class="w-10 h-10 rounded-xl bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center group-hover:shadow transition-shadow">
                            <svg class="w-5 h-5 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-sky-700 dark:text-sky-400 text-center leading-tight">İş İlanları</span>
                    </a>

                    {{-- Profile --}}
                    <a href="{{ route('profile.edit') }}"
                        class="group flex flex-col items-center gap-2 py-5 px-3 rounded-xl bg-amber-50 dark:bg-amber-900/10 hover:bg-amber-100 dark:hover:bg-amber-900/30 border border-amber-100 dark:border-amber-800/50 hover:border-amber-200 dark:hover:border-amber-700 transition-all duration-150">
                        <div
                            class="w-10 h-10 rounded-xl bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center group-hover:shadow transition-shadow">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-amber-700 dark:text-amber-400 text-center leading-tight">Profilim</span>
                    </a>

                    {{-- Discover --}}
                    <a href="{{ route('discover.index') }}"
                        class="group flex flex-col items-center gap-2 py-5 px-3 rounded-xl bg-fuchsia-50 dark:bg-fuchsia-900/10 hover:bg-fuchsia-100 dark:hover:bg-fuchsia-900/30 border border-fuchsia-100 dark:border-fuchsia-800/50 hover:border-fuchsia-200 dark:hover:border-fuchsia-700 transition-all duration-150">
                        <div
                            class="w-10 h-10 rounded-xl bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center group-hover:shadow transition-shadow">
                            <svg class="w-5 h-5 text-fuchsia-600 dark:text-fuchsia-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-fuchsia-700 dark:text-fuchsia-400 text-center leading-tight">Keşfet</span>
                    </a>

                    {{-- Premium --}}
                    @if(auth()->user()->isPremium())
                        <a href="{{ route('premium.services') }}"
                            class="group flex flex-col items-center gap-2 py-5 px-3 rounded-xl bg-gradient-to-br from-amber-50 dark:from-amber-900/20 to-orange-50 dark:to-orange-900/20 hover:from-amber-100 dark:hover:from-amber-900/40 hover:to-orange-100 dark:hover:to-orange-900/40 border border-amber-200 dark:border-amber-700 transition-all duration-150">
                            <div
                                class="w-10 h-10 rounded-xl bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center group-hover:shadow transition-shadow">
                                <span class="text-lg">⭐</span>
                            </div>
                            <span class="text-xs font-semibold text-amber-700 dark:text-amber-400 text-center leading-tight">Premium</span>
                        </a>
                    @else
                        <div
                            class="flex flex-col items-center gap-2 py-5 px-3 rounded-xl bg-neutral-50 dark:bg-gray-800 border border-neutral-200 dark:border-gray-700 cursor-not-allowed opacity-60">
                            <div class="w-10 h-10 rounded-xl bg-white dark:bg-gray-900 shadow-sm flex items-center justify-center">
                                <svg class="w-5 h-5 text-neutral-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-neutral-500 dark:text-gray-400 text-center leading-tight">Premium</span>
                        </div>
                    @endif

                </div>
            </div>

        </div>{{-- /max-w-7xl --}}
    </div>{{-- /min-h-screen --}}

</x-app-layout>
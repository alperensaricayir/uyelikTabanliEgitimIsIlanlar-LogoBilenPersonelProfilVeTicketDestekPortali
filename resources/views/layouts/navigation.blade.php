<nav x-data="{ open: false, showSearch: false }"
    class="bg-white dark:bg-gray-900 border-b border-neutral-200 dark:border-gray-700 sticky top-0 z-50 shadow-sm transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-4">

            {{-- Left: Logo + Brand --}}
            <div class="flex-shrink-0 flex items-center gap-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                    <img src="{{ asset('images/3s-logo.png') }}" class="h-8 w-auto hover:opacity-80 transition-opacity"
                        alt="3S Grup Logo">
                </a>
            </div>

            {{-- Center: Primary Nav Links --}}
            <div class="hidden md:flex items-center gap-1 flex-1 justify-center">
                @php
                    $navLinks = [
                        ['route' => 'trainings.index', 'label' => 'Eğitimler', 'icon' => '🎓'],
                        ['route' => 'discover.index', 'label' => 'Keşfet', 'icon' => '🔍'],
                        ['route' => 'jobs.index', 'label' => 'İş İlanları', 'icon' => '💼'],
                        ['route' => 'tickets.index', 'label' => 'Destek Ticketları', 'icon' => '📝'],
                    ];
                @endphp
                @foreach($navLinks as $link)
                    @php
                        $isActive = request()->routeIs($link['route']) || ($link['route'] === 'tickets.index' && request()->is('tickets*'));
                    @endphp
                    <a href="{{ route($link['route']) }}"
                        class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                                                  {{ $isActive ? 'bg-violet-50 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300' : 'text-neutral-600 dark:text-gray-300 hover:text-neutral-900 dark:hover:text-white hover:bg-neutral-100 dark:hover:bg-gray-800' }}">
                        {{ $link['label'] }}
                        @if($link['route'] === 'tickets.index' && auth()->check())
                            @php
                                $openTickets = auth()->user()->tickets()->openStatus()->count();
                            @endphp
                            @if($openTickets > 0)
                                <span
                                    class="bg-rose-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $openTickets }}</span>
                            @endif
                        @endif
                    </a>
                @endforeach
            </div>

            {{-- Right: Actions + Profile --}}
            <div class="flex items-center gap-2 flex-shrink-0">

                {{-- Dark Mode Toggle --}}
                <button type="button"
                    onclick="document.documentElement.classList.toggle('dark'); localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light'"
                    class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium bg-gray-100 hover:bg-gray-200 text-gray-900 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-100 transition-colors">
                    <span class="block dark:hidden">🌙 Dark</span>
                    <span class="hidden dark:block">☀️ Light</span>
                </button>

                {{-- Notifications Bell --}}
                <a href="{{ route('tickets.index') }}"
                    class="relative p-2 rounded-lg text-neutral-500 dark:text-gray-400 hover:text-neutral-800 dark:hover:text-gray-200 hover:bg-neutral-100 dark:hover:bg-gray-800 transition-colors"
                    title="Destek Ticket'ları">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </a>

                {{-- Premium badge --}}
                @if(auth()->user()->isPremium())
                    <a href="{{ route('premium.services') }}"
                        class="hidden sm:inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 hover:bg-amber-200 transition-colors">
                        ⭐ Premium
                    </a>
                @endif

                {{-- Profile Dropdown --}}
                <div x-data="{ dropOpen: false }" class="relative" @click.outside="dropOpen = false">
                    <button @click="dropOpen = !dropOpen"
                        class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-lg hover:bg-neutral-100 dark:hover:bg-gray-800 transition-colors">
                        <div
                            class="w-7 h-7 rounded-full bg-violet-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span
                            class="hidden sm:block text-sm font-medium text-neutral-700 dark:text-gray-200 max-w-24 truncate">
                            {{ auth()->user()->name }}
                        </span>
                        <svg class="w-3.5 h-3.5 text-neutral-400 dark:text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="dropOpen" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                        class="absolute right-0 top-full mt-2 w-52 bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-neutral-200 dark:border-gray-700 py-1 z-50"
                        style="display:none">

                        <div class="px-4 py-2 border-b border-neutral-100 dark:border-gray-800">
                            <p class="text-sm font-semibold text-neutral-900 dark:text-gray-100 truncate">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-neutral-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}
                            </p>
                        </div>

                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 dark:text-gray-300 hover:bg-neutral-50 dark:hover:bg-gray-800 transition-colors">
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 dark:text-gray-300 hover:bg-neutral-50 dark:hover:bg-gray-800 transition-colors">
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profilim
                        </a>
                        <a href="{{ route('tickets.index') }}"
                            class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 dark:text-gray-300 hover:bg-neutral-50 dark:hover:bg-gray-800 transition-colors">
                            <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            Destek
                        </a>

                        @if(auth()->user()->isAdminOrEditor())
                            <a href="{{ route('cms.courses.index') }}"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-violet-700 dark:text-violet-400 hover:bg-violet-50 dark:hover:bg-violet-900/30 transition-colors">
                                <svg class="w-4 h-4 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                CMS Panel
                            </a>
                        @endif

                        <div class="border-t border-neutral-100 dark:border-gray-800 mt-1 pt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Çıkış Yap
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Mobile hamburger --}}
                <button @click="open = !open"
                    class="md:hidden p-2 rounded-lg text-neutral-500 hover:text-neutral-800 hover:bg-neutral-100 transition-colors">
                    <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="open" x-transition
        class="md:hidden border-t border-neutral-100 dark:border-gray-800 bg-white dark:bg-gray-900"
        style="display:none">
        <div class="px-4 py-3 space-y-1">
            <button type="button"
                onclick="document.documentElement.classList.toggle('dark'); localStorage.theme = document.documentElement.classList.contains('dark') ? 'dark' : 'light'"
                class="flex w-full items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium bg-gray-100 text-gray-900 dark:bg-gray-800 dark:text-gray-100 mb-2">
                <span class="block dark:hidden">🌙 Dark Mode</span>
                <span class="hidden dark:block">☀️ Light Mode</span>
            </button>
            <a href="{{ route('trainings.index') }}"
                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-neutral-700 hover:bg-neutral-100">🎓
                Eğitimler</a>
            <a href="{{ route('discover.index') }}"
                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-neutral-700 hover:bg-neutral-100">🔍
                Keşfet</a>
            <a href="{{ route('jobs.index') }}"
                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-neutral-700 hover:bg-neutral-100">💼
                İş İlanları</a>
            <a href="{{ route('tickets.index') }}"
                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->is('tickets*') ? 'bg-violet-50 text-violet-700' : 'text-neutral-700 hover:bg-neutral-100' }}">📝
                Destek Ticketları
                @if(auth()->check())
                    @php
                        $openTickets = auth()->user()->tickets()->openStatus()->count();
                    @endphp
                    @if($openTickets > 0)
                        <span
                            class="bg-rose-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $openTickets }}</span>
                    @endif
                @endif
            </a>
            <a href="{{ route('profile.edit') }}"
                class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-neutral-700 hover:bg-neutral-100">👤
                Profilim</a>
            @if(auth()->user()->isPremium())
                <a href="{{ route('premium.services') }}"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-amber-700 hover:bg-amber-50">⭐
                    Premium</a>
            @endif
            <div class="border-t border-neutral-100 pt-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50">
                        Çıkış Yap
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
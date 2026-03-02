<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ $isOwner ? __('My Profile') : $user->name . ' - Profil' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <!-- Hero Banner -->
            <div class="h-48 w-full bg-gradient-to-r from-purple-600 to-fuchsia-600 rounded-t-2xl shadow-sm"></div>

            <!-- Profile Card Content -->
            <div
                class="-mt-16 bg-white dark:bg-gray-800 rounded-b-2xl shadow-xl border border-gray-100 dark:border-gray-700 p-8 pb-12 relative z-10 mx-4 md:mx-8">

                <!-- Main Header Row -->
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">

                    <!-- Avatar & Info -->
                    <div class="flex flex-col md:flex-row gap-6 items-center md:items-start text-center md:text-left">

                        <!-- Avatar -->
                        <div class="bg-white dark:bg-gray-800 p-1.5 rounded-full shadow-lg">
                            @if($user->profile_photo_path)
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}"
                                    class="h-28 w-28 rounded-full object-cover">
                            @else
                                <div
                                    class="h-28 w-28 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-4xl font-bold text-indigo-500 dark:text-indigo-300">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>

                        <!-- Info Text -->
                        <div class="mt-2 md:mt-4 space-y-1">
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                                {{ $user->name }}
                            </h1>
                            <p class="text-lg text-gray-600 dark:text-gray-300">
                                {{ $user->headline ?: ucfirst($user->role) }}
                            </p>
                            @if($user->city || $user->country)
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400 flex items-center justify-center md:justify-start gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ collect([$user->city, $user->country])->filter()->join(', ') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center gap-3">
                        @if($isOwner)
                            <a href="{{ route('profile.edit') }}"
                                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                                Edit Profile
                            </a>
                        @endif
                        <a href="{{ route('profile.public', $user) }}"
                            class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm font-medium rounded-lg transition shadow-sm flex items-center gap-2">
                            Public Profile
                            @if(!$user->is_profile_public)
                                <span
                                    class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Gizli</span>
                            @endif
                        </a>
                    </div>
                </div>

                <!-- Content Sections -->
                <div class="mt-12 space-y-10">

                    <!-- Bio -->
                    @if($user->bio)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">About</h3>
                            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                                {!! nl2br(e($user->bio)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Skills -->
                    @if($user->skills && count($user->skills) > 0)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Skills</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($user->skills as $skill)
                                    <span
                                        class="px-4 py-1.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-full text-sm font-medium border border-indigo-100 dark:border-indigo-800/50">
                                        {{ $skill }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Connect / Social Links -->
                    @php
                    $links = [
                    ['url' => $user->website_url, 'label' => 'Website', 'icon' => '<svg class="w-5 h-5 align-middle"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9">
                        </path>
                    </svg>'],
                    ['url' => $user->linkedin_url, 'label' => 'LinkedIn', 'icon' => '<svg class="w-5 h-5 align-middle"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                    </svg>'],
                    ['url' => $user->github_url, 'label' => 'GitHub', 'icon' => '<svg class="w-5 h-5 align-middle"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                    </svg>'],
                    ['url' => $user->instagram_url, 'label' => 'Instagram', 'icon' => '<svg class="w-5 h-5 align-middle"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                    </svg>'],
                    ['url' => $user->youtube_url, 'label' => 'YouTube', 'icon' => '<svg class="w-5 h-5 align-middle"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                    </svg>'],
                    ['url' => $user->twitter_url, 'label' => 'X (Twitter)', 'icon' => '<svg class="w-5 h-5 align-middle"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                    </svg>'],
                    ['url' => $user->behance_url, 'label' => 'Behance', 'icon' => '<svg class="w-5 h-5 align-middle"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M22 7h-7v-2h7v2zm1.726 10c-.442 1.297-2.029 3-5.101 3-3.074 0-5.564-1.729-5.564-5.675 0-3.91 2.325-5.92 5.466-5.92 3.082 0 4.964 1.782 5.375 4.426.078.506.109 1.188.095 2.14h-8.027c.13 3.211 3.483 3.312 4.588 2.029h3.168zm-7.686-2.904h4.658c-.183-1.726-1.503-2.502-2.584-2.502-1.096 0-1.898.711-2.074 2.502zm-5.04 4.904h-11v-15h7.355c2.628 0 4.847.674 4.847 3.58 0 2.247-1.427 3.036-2.564 3.352 1.543.376 3.123 1.564 3.123 4.095 0 2.946-2.316 3.973-5.004 3.973zm-7.5-6.837h4.043c1.393 0 2.327-.585 2.327-2.036 0-1.282-.676-1.996-2.128-1.996h-4.242v4.032zm0 2.296v4.541h4.088c1.654 0 2.65-.623 2.65-2.227 0-1.711-1.233-2.314-2.73-2.314h-4.008z" />
                    </svg>'],
                    ['url' => $user->dribbble_url, 'label' => 'Dribbble', 'icon' => '<svg class="w-5 h-5 align-middle"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm7.64 6.784c1.234 1.402 1.983 3.235 2.046 5.247-1.513-.399-3.411-.532-4.991-.397-1.071-2.94-2.383-5.698-3.957-8.239 3.007.484 5.666 1.796 6.902 3.389zm-13.791 7.822c-.633-3.692-.123-6.425.046-7.258 2.507.829 5.568 1.184 8.683.743.684 1.376 1.341 2.809 1.947 4.298-3.085 1.057-5.914 2.923-8.086 5.32-.964-1.121-1.638-2.529-1.956-4.07-.156-.2-.403-1.033-.634-1.033hnanm1.115-8.204c-.339.69-.646 1.543-.883 2.518-.112.46-.245 1.144-.336 1.84.098.816.591 3.424 1.196 6.958-3.045-2.327-4.981-6.109-4.981-10.368 0-.962.115-1.898.329-2.798 1.373 1.189 3.097 2.008 5.068 2.219-.138-.135-.268-.255-.393-.369zm10.749 6.273c.123.332.247.664.364 1.002-1.379 1.758-3.153 3.125-5.263 3.99-1.006-2.029-2.229-3.98-3.693-5.842 1.905-2.176 4.417-3.882 7.159-4.858 1.442 2.378 2.651 4.996 3.654 7.829 3.036-.37 5.503 1.218 5.753 1.396-.549 1.242-1.282 2.374-2.155 3.344-1.745-1.954-3.794-5.204-5.819-6.861z" />
                    </svg>']
                    ];
                    @php

                    <div class="pt-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Connect</h3>
                        <div class="flex flex-wrap gap-x-6 gap-y-4">
                            @foreach($links as $link)
                                @if($link['url'])
                                    <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer"
                                        class="flex items-center gap-2 text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition font-medium">
                                        {!! $link['icon'] !!} {{ $link['label'] }}
                                    </a>
                                @endif
                            @endforeach
                            @if(collect($links)->pluck('url')->filter()->isEmpty())
                                <p class="text-sm text-gray-400 dark:text-gray-500 italic">Hiçbir bağlantı eklenmedi.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Activity Stats -->
                    <div class="pt-8 pt-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Activity</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div
                                class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 text-center border border-gray-100 dark:border-gray-700">
                                <span
                                    class="block text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $user->enrollments()->count() ?? 0 }}</span>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Courses
                                    Enrolled</span>
                            </div>
                            <div
                                class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 text-center border border-gray-100 dark:border-gray-700">
                                <span
                                    class="block text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $user->tickets()->count() ?? 0 }}</span>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Support
                                    Tickets</span>
                            </div>
                            <div
                                class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 text-center border border-gray-100 dark:border-gray-700">
                                <span
                                    class="block text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ collect($user->likes_count)->sum() ?? 0 }}</span>
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Likes</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
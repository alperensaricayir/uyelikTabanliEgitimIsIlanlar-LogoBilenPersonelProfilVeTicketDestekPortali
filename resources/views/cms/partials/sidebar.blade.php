<nav class="w-64 bg-slate-900 text-white flex flex-col flex-shrink-0 min-h-screen">
    <div class="px-6 py-5 border-b border-slate-700">
        <a href="{{ route('cms.courses.index') }}" class="flex items-center gap-2">
            <span class="text-xl font-bold text-indigo-400">📚</span>
            <span class="font-bold text-lg text-white tracking-tight">EduPortal CMS</span>
        </a>
    </div>

    <div class="flex-1 py-4 space-y-1 px-3">
        <p class="cms-nav-section-label uppercase tracking-widest px-3 mb-2">İçerik</p>

        <a href="{{ route('cms.courses.index') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('cms.courses.*') && !request()->routeIs('cms.courses.trashed')
    ? 'bg-indigo-600 text-white cms-nav-active'
    : 'text-slate-200 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            Kurslar
        </a>

        <a href="{{ route('cms.courses.trashed') }}" class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                  {{ request()->routeIs('cms.courses.trashed')
    ? 'bg-red-700 text-white cms-nav-active'
    : 'text-slate-200 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Silinen Kurslar
        </a>

        <div class="border-t border-slate-700 my-3"></div>
        <p class="cms-nav-section-label uppercase tracking-widest px-3 mb-2">Site</p>

        <a href="{{ route('home') }}" target="_blank"
            class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-200 hover:bg-slate-800 hover:text-white transition-colors">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
            Siteyi Görüntüle
        </a>
    </div>

    <div class="px-3 py-4 border-t border-slate-700">
        <div class="flex items-center gap-3 px-3">
            <div
                class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-sm font-bold text-white flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                <p class="cms-user-role text-xs capitalize">{{ auth()->user()->role }}</p>
            </div>
        </div>
    </div>
</nav>
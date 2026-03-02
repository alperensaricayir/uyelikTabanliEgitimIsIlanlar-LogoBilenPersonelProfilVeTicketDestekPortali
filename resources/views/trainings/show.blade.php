<x-app-layout>
    <div class="py-12 bg-neutral-50 dark:bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <a href="{{ route('trainings.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm font-medium">
                    ← Tüm Eğitimlere Dön
                </a>
            </div>

            <div class="lg:grid lg:grid-cols-3 gap-8">
                {{-- LEFT COLUMN: 2 Cols Width --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- HERO SECTION --}}
                    <div class="relative w-full aspect-video rounded-2xl overflow-hidden bg-gray-900 border dark:border-gray-800 shadow-sm group">
                        @if($training->hero_poster_path || $training->thumbnail)
                            <img src="{{ $training->hero_poster_path ? asset('storage/' . $training->hero_poster_path) : $training->thumbnailUrl() }}" 
                                 alt="{{ $training->title }}" 
                                 class="absolute inset-0 w-full h-full object-cover">
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 to-gray-900"></div>
                        @endif

                        <div class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition-colors"></div>

                        @if($training->hero_video_url)
                            <a href="{{ $training->hero_video_url }}" target="_blank" class="absolute inset-0 flex items-center justify-center cursor-pointer">
                                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center border border-white/50 group-hover:scale-110 transition-transform shadow-lg">
                                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-white translate-x-1" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                </div>
                            </a>
                        @endif
                        
                        @if($training->is_premium_only)
                            <div class="absolute top-4 left-4">
                                <span class="bg-amber-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                    ⭐ Premium Eğitim
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- TITLE & META --}}
                    <div>
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 leading-tight">
                                {{ $training->title }}
                            </h1>
                            @if($training->price)
                                <div class="flex-shrink-0">
                                    <span class="inline-block bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 font-bold px-4 py-2 rounded-xl text-lg">
                                        ₺{{ number_format($training->price, 2, ',', '.') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-4 text-sm font-medium text-gray-600 dark:text-gray-400 mb-6">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                {{ $lessons->count() ?? 0 }} ders
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                &mdash; öğrenci
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ $training->updatedBy->name ?? 'Admin' }}
                            </div>
                        </div>

                        <div class="prose prose-neutral dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                            {!! nl2br(e($training->description)) !!}
                        </div>
                    </div>

                    {{-- MEETING / RESOURCES LINKS (Secondary Display) --}}
                    @if($training->meeting_url || $training->resources_url)
                        <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/50 rounded-2xl p-6">
                            <h3 class="font-bold text-indigo-900 dark:text-indigo-200 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                Eğitim Materyalleri
                            </h3>
                            @if($canViewGated)
                                <div class="flex flex-wrap gap-4">
                                    @if($training->meeting_url)
                                        <a href="{{ $training->meeting_url }}" target="_blank" class="inline-flex items-center justify-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-indigo-700 transition shadow-sm">
                                            🎥 Toplantı Linki
                                        </a>
                                    @endif
                                    @if($training->resources_url)
                                        <a href="{{ $training->resources_url }}" target="_blank" class="inline-flex items-center justify-center gap-2 bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-semibold hover:bg-emerald-700 transition shadow-sm">
                                            📚 Kaynaklar
                                        </a>
                                    @endif
                                </div>
                            @else
                                <div class="flex items-center gap-3 text-sm text-indigo-700 dark:text-indigo-300">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center flex-shrink-0">
                                        🔒
                                    </div>
                                    <p>Bu materyallere erişmek için Premium üyeliğe veya eğitime kayıt olmaya ihtiyacınız var.</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- DERS İÇERİĞİ --}}
                    <div class="bg-white dark:bg-gray-900 border border-neutral-200 dark:border-gray-800 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-6 border-b border-neutral-200 dark:border-gray-800 bg-neutral-50/50 dark:bg-gray-800/30">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Ders İçeriği</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $lessons->count() }} ders, izlemeye veya incelemeye hazır.</p>
                        </div>
                        
                        <div class="divide-y divide-neutral-100 dark:divide-gray-800">
                            @forelse($lessons as $index => $lesson)
                                @php
                                    // is_preview=true means everyone can view. Otherwise requires $canViewGated
                                    $isLocked = !$lesson->is_preview && !$canViewGated;
                                @endphp
                                
                                @if($isLocked)
                                    <div class="flex items-center gap-4 p-4 sm:p-5 opacity-75 bg-neutral-50/30 dark:bg-gray-900/30">
                                @else
                                    <a href="#" onclick="alert('Ders izleme modülü yakında aktif edilecektir.'); return false;" class="flex items-center gap-4 p-4 sm:p-5 hover:bg-neutral-50 dark:hover:bg-gray-800/50 transition cursor-pointer group">
                                @endif
                                    
                                    {{-- Lesson Number Badge --}}
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-neutral-100 dark:bg-gray-800 flex items-center justify-center text-sm font-bold text-gray-600 dark:text-gray-400 {{ !$isLocked ? 'group-hover:bg-indigo-100 group-hover:text-indigo-600 dark:group-hover:bg-indigo-900/50 dark:group-hover:text-indigo-400' : '' }} transition-colors">
                                        {{ $index + 1 }}
                                    </div>
                                    
                                    {{-- Title --}}
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-base font-semibold text-gray-900 dark:text-gray-200 truncate {{ !$isLocked ? 'group-hover:text-indigo-600 dark:group-hover:text-indigo-400' : '' }} transition-colors">
                                            {{ $lesson->title }}
                                        </h4>
                                        @if($lesson->is_preview)
                                            <span class="inline-block mt-1 text-[10px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-100 dark:text-emerald-400 dark:bg-emerald-900/30 px-2 py-0.5 rounded">Önizleme</span>
                                        @endif
                                    </div>

                                    {{-- Lock/Play Icon --}}
                                    <div class="flex-shrink-0 text-gray-400 dark:text-gray-500">
                                        @if($isLocked)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6 group-hover:text-indigo-500 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z" />
                                            </svg>
                                        @endif
                                    </div>

                                @if($isLocked)
                                    </div>
                                @else
                                    </a>
                                @endif
                                
                            @empty
                                <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    Henüz bu eğitime ders eklenmemiş.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Sticky Purchase Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-24">
                        <div class="bg-white dark:bg-gray-900 border border-neutral-200 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
                            <div class="mb-6">
                                <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-1">
                                    @if($training->price)
                                        ₺{{ number_format($training->price, 2, ',', '.') }}
                                    @else
                                        Ücretsiz
                                    @endif
                                </h3>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tüm içeriklere sınırsız erişim</p>
                            </div>

                            @php
                                $isEnrolled = auth()->check() ? $training->hasUserEnrolled(auth()->user()) : false;
                                $isPremium = auth()->check() ? auth()->user()->isPremium() : false;
                            @endphp

                            @if(auth()->guest())
                                <a href="{{ route('login') }}" class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-6 rounded-xl shadow-md hover:shadow-lg transition-all mb-4">
                                    Giriş Yapıp Kayıt Ol
                                </a>
                            @elseif($isEnrolled)
                                <button type="button" disabled class="w-full flex items-center justify-center gap-2 bg-green-100 text-green-700 border border-green-200 dark:bg-gray-700 dark:text-white dark:border-transparent font-bold py-3.5 px-6 rounded-xl shadow-inner cursor-not-allowed mb-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Kayıtlısın
                                </button>
                                @if($lessons->count() > 0)
                                    <a href="#" onclick="alert('Ders izleme modülü yakında aktif edilecektir.'); return false;" class="w-full flex items-center justify-center gap-2 border-2 border-emerald-600 text-emerald-700 dark:border-emerald-500 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 font-bold py-3 px-6 rounded-xl transition-all mb-4">
                                        Eğitime Git / Derslere Başla
                                    </a>
                                @endif
                            @elseif($training->is_premium_only && !$isPremium)
                                <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-premium-modal'))" class="w-full flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-bold py-3.5 px-6 rounded-xl shadow-md hover:shadow-lg transition-all mb-4">
                                    Premium Gerekli - Premium'a Geç
                                </button>
                            @else
                                <form action="{{ route('trainings.enroll', $training) }}" method="POST" class="w-full mb-4">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-6 rounded-xl shadow-md hover:shadow-lg transition-all">
                                        {{ $training->purchase_button_text ?? ($training->price ? str_replace('{price}', number_format($training->price, 2, ',', '.'), 'Şimdi Satın Al - ₺{price}') : 'Eğitime Kayıt Ol') }}
                                    </button>
                                </form>
                            @endif

                            <div class="pt-4 border-t border-neutral-100 dark:border-gray-800">
                                <h4 class="text-xs font-bold text-gray-900 dark:text-gray-200 uppercase tracking-wider mb-4">Bu eğitimde neler var?</h4>
                                
                                @php
                                    $features = $training->promo_features ?? [
                                        $lessons->count() . ' video ders',
                                        'Sertifika (yakında)',
                                        'Ömür boyu erişim'
                                    ];
                                @endphp

                                <ul class="space-y-3">
                                    @foreach($features as $feature)
                                        <li class="flex items-start gap-3 text-sm font-medium text-gray-600 dark:text-gray-400">
                                            <svg class="w-5 h-5 flex-shrink-0 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
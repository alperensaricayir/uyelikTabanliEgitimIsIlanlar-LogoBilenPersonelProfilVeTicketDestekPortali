<x-app-layout>
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            {{-- Breadcrumb --}}
            <nav class="flex text-sm text-gray-500 dark:text-gray-400 mb-6 font-medium" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                            Ana Sayfa
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <a href="{{ route('trainings.show', $training) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">{{ Str::limit($training->title, 30) }}</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="text-gray-900 dark:text-gray-100">{{ Str::limit($lesson->title, 30) }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                
                {{-- MAIN CONTENT --}}
                <div class="lg:col-span-3 space-y-6">
                    
                    {{-- Video Player --}}
                    @if($lesson->video_url)
                        <div class="bg-black rounded-2xl overflow-hidden aspect-video shadow-lg ring-1 ring-gray-900/10 dark:ring-white/10">
                            {{-- Try to embed YouTube or Vimeo natively if recognized, else simple iframe. (Assuming standard iframe setup for now) --}}
                            @php
                                $embedUrl = $lesson->video_url;
                                if(str_contains($embedUrl, 'youtube.com/watch?v=')) {
                                    $embedUrl = str_replace('watch?v=', 'embed/', $embedUrl);
                                } elseif (str_contains($embedUrl, 'youtu.be/')) {
                                    $embedUrl = str_replace('youtu.be/', 'youtube.com/embed/', $embedUrl);
                                }
                            @endphp
                            <iframe 
                                src="{{ $embedUrl }}" 
                                class="w-full h-full" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                            </iframe>
                        </div>
                    @endif

                    {{-- Lesson Title & Details --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 md:p-8 shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                                {{ $lesson->title }}
                            </h1>
                            @if($lesson->is_preview)
                                <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-400 rounded-full border border-green-200 dark:border-green-800/50">
                                    Önizleme Dersi
                                </span>
                            @endif
                        </div>

                        {{-- Rich Text Content --}}
                        <div class="prose prose-indigo max-w-none dark:prose-invert">
                            {!! $lesson->content ?? '<p class="text-gray-500 italic">Bu ders için henüz yazılı bir içerik eklenmemiş.</p>' !!}
                        </div>
                    </div>
                </div>

                {{-- SIDEBAR: Content Outline --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 sticky top-8 overflow-hidden">
                        
                        <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/80">
                            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">Eğitim İçeriği</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $otherLessons->count() }} ders listeleniyor.</p>
                        </div>

                        <div class="divide-y divide-gray-100 dark:divide-gray-700/50 max-h-[60vh] overflow-y-auto">
                            @foreach($otherLessons as $index => $otherLesson)
                                @php
                                    $isActive = $otherLesson->id === $lesson->id;
                                    $isLocked = !$otherLesson->is_preview && !$isEnrolled;
                                @endphp
                                
                                @if($isLocked)
                                    <div class="group flex items-center justify-between p-4 cursor-not-allowed bg-gray-50 dark:bg-gray-800/30 opacity-75">
                                @else
                                    <a href="{{ route('trainings.lessons.show', [$training, $otherLesson]) }}" 
                                       class="group flex items-center justify-between p-4 hover:bg-indigo-50 dark:hover:bg-indigo-900/10 transition-colors {{ $isActive ? 'bg-indigo-50/70 dark:bg-indigo-900/20 shadow-[inset_4px_0_0_0_#6366f1]' : '' }}">
                                @endif
                                
                                    <div class="flex items-center gap-3 pr-4">
                                        {{-- Lesson Number Bubble --}}
                                        <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full text-xs font-bold transition-colors
                                            {{ $isActive ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 group-hover:bg-indigo-100 group-hover:text-indigo-600 dark:group-hover:bg-indigo-900/50 dark:group-hover:text-indigo-400' }}">
                                            {{ $index + 1 }}
                                        </div>
                                        
                                        {{-- Title & Meta --}}
                                        <div>
                                            <p class="text-sm font-semibold transition-colors {{ $isActive ? 'text-indigo-700 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400' }}">
                                                {{ Str::limit($otherLesson->title, 40) }}
                                            </p>
                                            
                                            <div class="flex items-center gap-2 mt-1">
                                                @if($otherLesson->is_preview)
                                                    <span class="inline-flex text-[10px] font-bold px-1.5 py-0.5 rounded-sm bg-green-100 text-green-700 tracking-wide uppercase dark:bg-green-900/30 dark:text-green-400">Önizleme</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Lock/Play Icon --}}
                                    <div class="flex-shrink-0 text-gray-400 dark:text-gray-500">
                                        @if($isLocked)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        @elseif($isActive)
                                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 24 24">
                                                <rect x="6" y="5" width="4" height="14" rx="1"/>
                                                <rect x="14" y="5" width="4" height="14" rx="1"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 group-hover:text-indigo-500 transition-all" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z" />
                                            </svg>
                                        @endif
                                    </div>

                                @if($isLocked)
                                    </div>
                                @else
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Eğitimler') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($trainings as $training)
                    <div
                        class="bg-white dark:bg-gray-900 shadow-lg hover:shadow-xl rounded-xl overflow-hidden border border-gray-100 dark:border-gray-800 transition-all duration-200 hover:scale-[1.02] flex flex-col h-full relative">

                        {{-- Premium Badge --}}
                        @if($training->is_premium_only)
                            <div class="absolute top-3 right-3 z-10">
                                <span
                                    class="bg-amber-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow tracking-wide">
                                    ⭐ Premium
                                </span>
                            </div>
                        @endif

                        {{-- Thumbnail Area --}}
                        <div
                            class="relative w-full h-44 overflow-hidden rounded-t-xl bg-gray-100 dark:bg-gray-800 flex-shrink-0">
                            @if($training->thumbnailUrl())
                                <img src="{{ $training->thumbnailUrl() }}" alt="{{ $training->title }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-indigo-50 to-violet-50 dark:from-indigo-900/20 dark:to-violet-900/20 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-indigo-300 dark:text-indigo-500/50" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Content Area --}}
                        <div class="p-5 flex flex-col flex-1 mt-2">
                            <h3 class="font-bold text-lg text-gray-800 dark:text-gray-100 mb-2 line-clamp-2 leading-tight">
                                {{ $training->title }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-6 line-clamp-3 leading-relaxed flex-1">
                                {{ Str::limit($training->description, 120) }}
                            </p>
                            <a href="{{ route('trainings.show', $training->slug) }}"
                                class="inline-block text-center w-full bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 font-semibold text-sm px-4 py-2.5 rounded-lg hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-500 transition-colors shadow-sm">
                                Detayı Gör
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="col-span-3 text-gray-500 dark:text-gray-400 text-center py-12">Henüz eğitim bulunmuyor.</p>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $trainings->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
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
                        class="bg-white dark:bg-gray-900 shadow rounded-lg overflow-hidden dark:border dark:border-gray-800">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="font-bold text-lg text-gray-800 dark:text-gray-100">{{ $training->title }}</h3>
                                @if($training->is_premium_only)
                                    <span
                                        class="text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 px-2 py-1 rounded-full ml-2 whitespace-nowrap">
                                        ⭐ Premium
                                    </span>
                                @endif
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-3">
                                {{ Str::limit($training->description, 120) }}
                            </p>
                            <a href="{{ route('trainings.show', $training->slug) }}"
                                class="inline-block bg-indigo-600 text-white text-sm px-4 py-2 rounded hover:bg-indigo-700 transition">
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
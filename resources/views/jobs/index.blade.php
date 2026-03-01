<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">İş İlanları</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- İş Uyarısı --}}
            @auth
                <div class="bg-white shadow rounded-lg p-6 mb-8">
                    <h3 class="font-semibold text-gray-700 mb-3">🔔 İş Uyarısı Kur</h3>
                    @if(session('success'))
                        <div class="mb-3 text-green-600 text-sm">{{ session('success') }}</div>
                    @endif
                    <form method="POST" action="{{ route('jobs.alert.store') }}">
                        @csrf
                        <div class="flex gap-3 flex-wrap">
                            <input type="text" name="keywords[]" placeholder="Örn: Laravel"
                                class="border-gray-300 rounded-md flex-1 min-w-[150px] focus:ring-indigo-500 focus:border-indigo-500">
                            <input type="text" name="keywords[]" placeholder="Örn: PHP"
                                class="border-gray-300 rounded-md flex-1 min-w-[150px] focus:ring-indigo-500 focus:border-indigo-500">
                            <input type="text" name="keywords[]" placeholder="Örn: Vue"
                                class="border-gray-300 rounded-md flex-1 min-w-[150px] focus:ring-indigo-500 focus:border-indigo-500">
                            <button type="submit"
                                class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition whitespace-nowrap">
                                Uyarı Kaydet
                            </button>
                        </div>
                        @if($alert)
                            <p class="text-xs text-gray-500 mt-2">
                                Mevcut uyarınız: <strong>{{ implode(', ', $alert->keywords) }}</strong>
                            </p>
                        @endif
                    </form>
                </div>
            @endauth

            {{-- İlan Listesi --}}
            <div class="space-y-4">
                @forelse($jobs as $job)
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-bold text-gray-800 text-lg">{{ $job->title }}</h3>
                                <p class="text-gray-500 text-sm">{{ $job->company_name }}</p>
                            </div>
                            <a href="{{ route('jobs.show', $job) }}"
                                class="text-indigo-600 hover:underline text-sm whitespace-nowrap ml-4">
                                Detay →
                            </a>
                        </div>
                        @if($job->tags)
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach($job->tags as $tag)
                                    <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">{{ $tag }}</span>
                                @endforeach
                            </div>
                        @endif
                        <p class="text-gray-600 text-sm mt-3 line-clamp-2">{{ Str::limit($job->description, 150) }}</p>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-12">Şu an aktif ilan bulunmuyor.</p>
                @endforelse
            </div>

            <div class="mt-6">{{ $jobs->links() }}</div>
        </div>
    </div>
</x-app-layout>
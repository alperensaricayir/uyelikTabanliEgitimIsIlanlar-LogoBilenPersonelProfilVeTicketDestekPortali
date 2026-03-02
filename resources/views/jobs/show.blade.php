<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">İş İlanı Detayı</h2>
            <a href="{{ route('jobs.index') }}"
                class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; İlanlara Dön</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 border dark:border-gray-800 shadow rounded-lg p-8">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $job->title }}</h1>
                        <p class="text-lg text-gray-600 dark:text-gray-300">{{ $job->company_name }}</p>
                    </div>
                    @if($job->application_url)
                        <a href="{{ $job->application_url }}" target="_blank"
                            class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm whitespace-nowrap">
                            🚀 Başvur
                        </a>
                    @endif
                </div>

                @if($job->tags)
                    @php
                        $tags = is_string($job->tags) ? [$job->tags] : (is_array($job->tags) ? $job->tags : []);
                    @endphp
                    @if(count($tags) > 0)
                        <div class="flex flex-wrap gap-2 mb-8">
                            @foreach($tags as $tag)
                                <span
                                    class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 text-sm px-3 py-1.5 rounded-full">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif
                @endif

                <div class="prose max-w-none dark:prose-invert">
                    @if($job->content)
                        {!! $job->content !!}
                    @else
                        <p class="whitespace-pre-wrap">{{ $job->description }}</p>
                    @endif
                </div>

                @if($job->application_url)
                    <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-800">
                        <a href="{{ $job->application_url }}" target="_blank"
                            class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 border border-transparent text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 shadow transition-colors">
                            Hemen Başvur &rarr;
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
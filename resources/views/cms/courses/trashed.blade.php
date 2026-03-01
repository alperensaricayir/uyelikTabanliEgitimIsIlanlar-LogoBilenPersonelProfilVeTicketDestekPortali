@extends('cms.layouts.app')
@section('title', 'Silinen Kurslar')

@section('content')
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('cms.courses.index') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <h2 class="text-2xl font-bold text-gray-900">Silinen Kurslar</h2>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            @if($courses->isEmpty())
                <div class="p-12 text-center text-gray-400">
                    <p class="text-3xl mb-2">🗑</p>
                    <p class="text-sm">Silinmiş kurs bulunmuyor.</p>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Başlık</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Silinme Tarihi</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($courses as $course)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <p class="font-medium text-gray-800">{{ $course->title }}</p>
                                    <p class="text-xs text-gray-400 font-mono">{{ $course->slug }}</p>
                                </td>
                                <td class="px-4 py-3 text-gray-500 text-sm">{{ $course->deleted_at->format('d.m.Y H:i') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST" action="{{ route('cms.courses.restore', $course->id) }}">
                                        @csrf
                                        <button
                                            class="px-3 py-1.5 text-xs bg-green-100 text-green-700 rounded-lg hover:bg-green-200 font-medium">
                                            ↩ Geri Yükle
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($courses->hasPages())
                    <div class="px-4 py-3 border-t border-gray-100">
                        {{ $courses->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
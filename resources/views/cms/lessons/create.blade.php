@extends('cms.layouts.app')
@section('title', 'Ders Oluştur – ' . $course->title)

@section('content')
    <div class="max-w-3xl space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('cms.courses.lessons.index', $course) }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Yeni Ders</h2>
                <p class="text-sm text-gray-400">{{ $course->title }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('cms.courses.lessons.store', $course) }}" class="space-y-5">
            @csrf
            @include('cms.lessons._form', ['lesson' => null, 'course' => $course])
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700">Oluştur</button>
                <a href="{{ route('cms.courses.lessons.index', $course) }}"
                    class="px-6 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200">İptal</a>
            </div>
        </form>
    </div>
@endsection
@extends('cms.layouts.app')
@section('title', 'Kurs Oluştur')

@section('content')
    <div class="max-w-3xl space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('cms.courses.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-gray-900">Yeni Kurs</h2>
        </div>

        <form method="POST" action="{{ route('cms.courses.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @include('cms.courses._form', ['course' => null])

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    Oluştur
                </button>
                <a href="{{ route('cms.courses.index') }}"
                    class="px-6 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    İptal
                </a>
            </div>
        </form>
    </div>
@endsection
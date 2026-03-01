@extends('cms.layouts.app')
@section('title', $course->title . ' – Dersler')

@section('content')
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('cms.courses.show', $course) }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Dersler</h2>
                    <p class="text-sm text-gray-400">{{ $course->title }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('cms.courses.lessons.trashed', $course) }}"
                    class="px-3 py-2 text-sm text-gray-500 bg-gray-100 rounded-lg hover:bg-gray-200">
                    🗑 Silinenler
                </a>
                <a href="{{ route('cms.courses.lessons.create', $course) }}"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                    + Ders Ekle
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <p class="text-sm text-gray-500 mb-4">Dersleri sürükleyerek sıralamayı değiştirebilirsiniz.</p>

            @if($lessons->isEmpty())
                <div class="py-10 text-center text-gray-400">
                    <p class="text-3xl mb-2">📝</p>
                    <p class="text-sm">Henüz ders eklenmemiş.</p>
                </div>
            @else
                <ul id="lessonList" class="space-y-2">
                    @foreach($lessons as $lesson)
                        <li class="lesson-item flex items-center gap-3 p-3 bg-gray-50 border border-gray-200 rounded-lg cursor-grab active:cursor-grabbing"
                            data-id="{{ $lesson->id }}">
                            <span class="text-gray-300 select-none">⣿</span>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-sm text-gray-800">{{ $lesson->title }}</p>
                                <p class="text-xs text-gray-400">{{ $lesson->slug }}</p>
                            </div>
                            @php $lc = $lesson->status->color() @endphp
                            <span
                                class="text-xs px-2 py-0.5 rounded-full font-medium flex-shrink-0
                                        {{ $lc === 'green' ? 'bg-green-100 text-green-700' : ($lc === 'yellow' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600') }}">
                                {{ $lesson->status->label() }}
                            </span>
                            @if($lesson->is_preview)
                                <span class="text-xs text-blue-500 flex-shrink-0">👁 Önizleme</span>
                            @endif
                            <div class="flex gap-1 flex-shrink-0">
                                <a href="{{ route('lessons.edit', $lesson) }}"
                                    class="px-2 py-1 text-xs text-indigo-600 bg-indigo-50 rounded hover:bg-indigo-100">Düzenle</a>
                                @can('delete', $lesson)
                                    <form method="POST" action="{{ route('lessons.destroy', $lesson) }}"
                                        onsubmit="return confirm('Bu dersi silmek istediğinizden emin misiniz?')">
                                        @csrf @method('DELETE')
                                        <button class="px-2 py-1 text-xs text-red-600 bg-red-50 rounded hover:bg-red-100">Sil</button>
                                    </form>
                                @endcan
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div id="reorderStatus" class="mt-3 text-xs text-gray-400 hidden">Kaydediliyor...</div>
            @endif
        </div>
    </div>

    <script>
        // Simple drag-and-drop reorder using Sortable-like approach with native DnD
        let dragSrc = null;
        const list = document.getElementById('lessonList');
        if (list) {
            list.querySelectorAll('.lesson-item').forEach(item => {
                item.draggable = true;
                item.addEventListener('dragstart', function (e) { dragSrc = this; this.classList.add('opacity-50'); });
                item.addEventListener('dragend', function () { this.classList.remove('opacity-50'); saveOrder(); });
                item.addEventListener('dragover', function (e) { e.preventDefault(); list.insertBefore(dragSrc, this.nextSibling); });
            });

            function saveOrder() {
                const ids = [...list.querySelectorAll('.lesson-item')].map(i => i.dataset.id);
                const status = document.getElementById('reorderStatus');
                status.classList.remove('hidden');
                fetch("{{ route('cms.courses.lessons.reorder', $course) }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ order: ids })
                }).then(r => r.json()).then(() => {
                    status.textContent = '✓ Kaydedildi';
                    setTimeout(() => status.classList.add('hidden'), 2000);
                }).catch(() => { status.textContent = '✗ Hata'; });
            }
        }
    </script>
@endsection
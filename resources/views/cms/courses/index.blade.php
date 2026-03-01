@extends('cms.layouts.app')
@section('title', 'Kurslar')

@section('content')
    <div class="space-y-4">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Kurslar</h2>
                <p class="text-sm text-gray-500 mt-0.5">Tüm kursları yönetin</p>
            </div>
            <a href="{{ route('cms.courses.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Yeni Kurs
            </a>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('cms.courses.index') }}"
            class="bg-white rounded-xl border border-gray-200 p-4 flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-48">
                <label class="block text-xs font-medium text-gray-600 mb-1">Ara</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Kurs başlığı..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Durum</label>
                <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500">
                    <option value="">Tümü</option>
                    @foreach(\App\Enums\ContentStatus::cases() as $s)
                        <option value="{{ $s->value }}" @selected(request('status') === $s->value)>{{ $s->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Erişim</label>
                <select name="premium" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500">
                    <option value="">Tümü</option>
                    <option value="1" @selected(request('premium') === '1')>Premium</option>
                    <option value="0" @selected(request('premium') === '0')>Ücretsiz</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700">Filtrele</button>
                <a href="{{ route('cms.courses.index') }}"
                    class="px-4 py-2 bg-gray-100 text-gray-600 text-sm rounded-lg hover:bg-gray-200">Temizle</a>
            </div>
        </form>

        {{-- Bulk action form --}}
        <form id="bulkForm" method="POST" action="{{ route('cms.courses.bulk') }}">
            @csrf
            <input type="hidden" name="action" id="bulkAction">

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                {{-- Bulk bar --}}
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-600">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-indigo-600">
                        Tümünü seç
                    </label>
                    <div class="flex gap-2">
                        <button type="button" onclick="doBulk('publish')"
                            class="px-3 py-1.5 text-xs bg-green-100 text-green-700 rounded-lg hover:bg-green-200 font-medium">
                            ✓ Yayınla
                        </button>
                        <button type="button" onclick="doBulk('unpublish')"
                            class="px-3 py-1.5 text-xs bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 font-medium">
                            ⊘ Taslağa al
                        </button>
                        @can('delete', \App\Models\Training::class)
                            <button type="button" onclick="doBulk('delete')"
                                class="px-3 py-1.5 text-xs bg-red-100 text-red-700 rounded-lg hover:bg-red-200 font-medium"
                                onclick="return confirm('Seçilen kursları silmek istediğinize emin misiniz?')">
                                🗑 Sil
                            </button>
                        @endcan
                    </div>
                </div>

                {{-- Table --}}
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="w-8 px-4 py-3"></th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'title', 'dir' => request('dir') === 'asc' ? 'desc' : 'asc']) }}"
                                    class="hover:underline">
                                    Başlık {{ request('sort') === 'title' ? (request('dir') === 'asc' ? '↑' : '↓') : '' }}
                                </a>
                            </th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Durum</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Dersler</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Erişim</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'dir' => request('dir') === 'asc' ? 'desc' : 'asc']) }}"
                                    class="hover:underline">
                                    Tarih
                                    {{ request('sort') === 'created_at' ? (request('dir') === 'asc' ? '↑' : '↓') : '' }}
                                </a>
                            </th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-600">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($courses as $course)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3">
                                    <input type="checkbox" name="ids[]" value="{{ $course->id }}"
                                        class="rowCheck rounded border-gray-300 text-indigo-600">
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        @if($course->thumbnail)
                                            <img src="{{ $course->thumbnailUrl() }}"
                                                class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                                        @else
                                            <div
                                                class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-400 flex-shrink-0 text-lg">
                                                📚</div>
                                        @endif
                                        <div>
                                            <a href="{{ route('cms.courses.show', $course) }}"
                                                class="font-semibold text-gray-900 hover:text-indigo-600">
                                                {{ $course->title }}
                                            </a>
                                            <p class="text-xs text-gray-400">{{ $course->slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @php $color = $course->status->color() @endphp
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                            {{ $color === 'green' ? 'bg-green-100 text-green-700' : ($color === 'yellow' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600') }}">
                                        {{ $course->status->label() }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $course->lessons()->count() }} ders
                                </td>
                                <td class="px-4 py-3">
                                    @if($course->is_premium_only)
                                        <span
                                            class="px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700">Premium</span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Ücretsiz</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-500 text-xs">
                                    {{ $course->created_at->format('d.m.Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('cms.courses.show', $course) }}"
                                            class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg"
                                            title="Görüntüle">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('cms.courses.edit', $course) }}"
                                            class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg"
                                            title="Düzenle">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        @can('delete', $course)
                                            <form method="POST" action="{{ route('cms.courses.destroy', $course) }}"
                                                onsubmit="return confirm('Bu kursu silmek istediğinizden emin misiniz?')">
                                                @csrf @method('DELETE')
                                                <button class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg"
                                                    title="Sil">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                                    <p class="text-4xl mb-3">📭</p>
                                    <p>Kurs bulunamadı.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                @if($courses->hasPages())
                    <div class="px-4 py-3 border-t border-gray-100">
                        {{ $courses->links() }}
                    </div>
                @endif
            </div>
        </form>

    </div>

    <script>
        document.getElementById('selectAll').addEventListener('change', function () {
            document.querySelectorAll('.rowCheck').forEach(cb => cb.checked = this.checked);
        });
        function doBulk(action) {
            const checked = document.querySelectorAll('.rowCheck:checked');
            if (!checked.length) { alert('Lütfen en az bir kurs seçin.'); return; }
            if (action === 'delete' && !confirm('Seçilen kursları silmek istediğinize emin misiniz?')) return;
            document.getElementById('bulkAction').value = action;
            document.getElementById('bulkForm').submit();
        }
    </script>
@endsection
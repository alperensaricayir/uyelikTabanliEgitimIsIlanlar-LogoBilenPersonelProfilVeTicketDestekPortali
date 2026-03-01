@extends('cms.layouts.app')
@section('title', $course->title)

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('cms.courses.index') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $course->title }}</h2>
                    <div class="flex items-center gap-2 mt-0.5">
                        @php $color = $course->status->color() @endphp
                        <span
                            class="text-xs px-2 py-0.5 rounded-full font-semibold
                            {{ $color === 'green' ? 'bg-green-100 text-green-700' : ($color === 'yellow' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ $course->status->label() }}
                        </span>
                        @if($course->is_premium_only)
                            <span
                                class="text-xs px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 font-semibold">Premium</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('cms.courses.edit', $course) }}"
                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                    Düzenle
                </a>
                @can('delete', $course)
                    <form method="POST" action="{{ route('cms.courses.destroy', $course) }}"
                        onsubmit="return confirm('Bu kursu silmek istediğinizden emin misiniz?')">
                        @csrf @method('DELETE')
                        <button
                            class="px-4 py-2 bg-red-100 text-red-600 text-sm font-medium rounded-lg hover:bg-red-200">Sil</button>
                    </form>
                @endcan
            </div>
        </div>

        {{-- Tabs --}}
        <div class="border-b border-gray-200">
            <nav class="flex gap-0 -mb-px">
                @foreach(['overview' => 'Genel Bakış', 'lessons' => 'Dersler', 'settings' => 'Ayarlar'] as $tabKey => $tabLabel)
                    <a href="?tab={{ $tabKey }}"
                        class="px-5 py-3 text-sm font-medium border-b-2 transition-colors
                                  {{ $tab === $tabKey ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        {{ $tabLabel }}
                    </a>
                @endforeach
            </nav>
        </div>

        {{-- Tab Content --}}

        @if($tab === 'overview')
            <div class="grid grid-cols-3 gap-6">
                <div class="col-span-2 bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    @if($course->thumbnail)
                        <img src="{{ $course->thumbnailUrl() }}" class="w-full h-48 object-cover rounded-lg">
                    @endif
                    <h3 class="font-semibold text-gray-800">Açıklama</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $course->description }}</p>

                    @if($course->meeting_url)
                        <div class="text-sm"><span class="font-medium text-gray-700">Toplantı URL:</span>
                            <a href="{{ $course->meeting_url }}" class="text-indigo-600 ml-1 hover:underline"
                                target="_blank">{{ $course->meeting_url }}</a>
                        </div>
                    @endif
                    @if($course->resources_url)
                        <div class="text-sm"><span class="font-medium text-gray-700">Kaynaklar URL:</span>
                            <a href="{{ $course->resources_url }}" class="text-indigo-600 ml-1 hover:underline"
                                target="_blank">{{ $course->resources_url }}</a>
                        </div>
                    @endif
                </div>

                <div class="space-y-4">
                    <div class="bg-white rounded-xl border border-gray-200 p-4 space-y-3 text-sm">
                        <h3 class="font-semibold text-gray-800">Bilgiler</h3>
                        <div class="flex justify-between text-gray-600">
                            <span>Slug</span><span class="font-mono text-xs">{{ $course->slug }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Oluşturulma</span><span>{{ $course->created_at->format('d.m.Y') }}</span>
                        </div>
                        @if($course->published_at)
                            <div class="flex justify-between text-gray-600">
                                <span>Yayımlandı</span><span>{{ $course->published_at->format('d.m.Y H:i') }}</span>
                            </div>
                        @endif
                        @if($course->updatedBy)
                            <div class="flex justify-between text-gray-600">
                                <span>Son güncelleme</span><span>{{ $course->updatedBy->name }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Revisions --}}
                    @if($revisions->isNotEmpty())
                        <div class="bg-white rounded-xl border border-gray-200 p-4 space-y-2 text-sm">
                            <h3 class="font-semibold text-gray-800">Son Revizyonlar (Açıklama)</h3>
                            @foreach($revisions as $rev)
                                <div class="border-l-2 border-gray-200 pl-3 text-xs text-gray-500">
                                    <p>{{ $rev->created_at->diffForHumans() }} · {{ $rev->user?->name }}</p>
                                    <p class="text-gray-400 truncate">{{ Str::limit($rev->value, 80) }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        @elseif($tab === 'lessons')
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Dersler ({{ $lessons->count() }})</h3>
                    <a href="{{ route('cms.courses.lessons.create', $course) }}"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                        + Ders Ekle
                    </a>
                </div>
                @if($lessons->isEmpty())
                    <div class="p-10 text-center text-gray-400">
                        <p class="text-3xl mb-2">📝</p>
                        <p class="text-sm">Henüz ders eklenmemiş.</p>
                    </div>
                @else
                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 w-12">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Başlık</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Durum</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Önizleme</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($lessons as $lesson)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-400">{{ $lesson->sort_order }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $lesson->title }}</td>
                                    <td class="px-4 py-3">
                                        @php $lc = $lesson->status->color() @endphp
                                        <span
                                            class="text-xs px-2 py-0.5 rounded-full font-medium
                                                    {{ $lc === 'green' ? 'bg-green-100 text-green-700' : ($lc === 'yellow' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600') }}">
                                            {{ $lesson->status->label() }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($lesson->is_preview)
                                            <span class="text-xs text-blue-600 font-medium">✓ Önizleme</span>
                                        @else
                                            <span class="text-xs text-gray-400">–</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('lessons.edit', [$lesson]) }}"
                                            class="text-xs text-indigo-600 hover:underline mr-2">Düzenle</a>
                                        @can('delete', $lesson)
                                            <form method="POST" action="{{ route('lessons.destroy', [$lesson]) }}" class="inline"
                                                onsubmit="return confirm('Bu dersi silmek istediğinizden emin misiniz?')">
                                                @csrf @method('DELETE')
                                                <button class="text-xs text-red-500 hover:underline">Sil</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <a href="{{ route('cms.courses.lessons.index', $course) }}" class="text-sm text-indigo-600 hover:underline">
                → Ders listesini ve sıralamayı yönet
            </a>

        @elseif($tab === 'settings')
            <div class="max-w-xl bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                <h3 class="font-semibold text-gray-800">Tehlikeli Bölge</h3>
                @can('delete', $course)
                    <div class="flex items-center justify-between py-3 border-t border-gray-100">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Kursu Sil</p>
                            <p class="text-xs text-gray-400 mt-0.5">Kurs silinir fakat geri yüklenebilir.</p>
                        </div>
                        <form method="POST" action="{{ route('cms.courses.destroy', $course) }}"
                            onsubmit="return confirm('Bu kursu silmek istediğinizden emin misiniz?')">
                            @csrf @method('DELETE')
                            <button
                                class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">Sil</button>
                        </form>
                    </div>
                @endcan
            </div>
        @endif

    </div>
@endsection
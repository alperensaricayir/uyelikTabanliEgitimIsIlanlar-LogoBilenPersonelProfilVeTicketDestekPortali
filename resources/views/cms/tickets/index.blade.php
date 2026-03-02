@extends('cms.layouts.app')

@section('title', 'Destek Biletleri')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Sistem Biletleri</h2>
                <p class="text-sm text-slate-500 mt-1">Kullanıcıların açtığı tüm destek taleplerini buradan
                    yönetebilirsiniz.</p>
            </div>
        </div>

        {{-- Filters & Search --}}
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <form method="GET" action="{{ route('cms.tickets.index') }}"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Search Box --}}
                <div class="lg:col-span-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Konu veya ID ara..."
                        class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                {{-- Status Filter --}}
                <div class="flex gap-2 lg:col-span-2">
                    <select name="status"
                        class="block w-full py-2 pl-3 pr-10 border border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Tüm Durumlar</option>
                        <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Açık</option>
                        <option value="answered" {{ request('status') === 'answered' ? 'selected' : '' }}>Yanıtlandı</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Bekliyor</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Kapalı</option>
                    </select>
                    <button type="submit"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition-colors border border-slate-300 shrink-0">
                        Filtrele
                    </button>
                </div>
            </form>
        </div>

        {{-- Tickets Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">ID
                                / Konu</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Kullanıcı</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Durum</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Son Güncellenme</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                İşlemler</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse($tickets as $ticket)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-slate-900">#{{ $ticket->id }}</div>
                                    <div class="text-sm text-slate-500 truncate max-w-xs">{{ $ticket->subject }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                            {{ strtoupper(substr($ticket->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-slate-900">
                                                {{ $ticket->user->name ?? 'Bilinmeyen' }}</div>
                                            <div class="text-xs text-slate-500">{{ $ticket->user->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ticket->statusBadgeClass() }}">
                                        {{ $ticket->statusLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $ticket->last_reply_at ? $ticket->last_reply_at->format('d M Y H:i') : $ticket->updated_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('cms.tickets.show', $ticket) }}"
                                            class="text-indigo-600 hover:text-indigo-900 font-semibold"
                                            title="Görüntüle">İncele</a>

                                        <span class="text-slate-300">|</span>

                                        <form method="POST" action="{{ route('cms.tickets.destroy', $ticket) }}"
                                            class="inline-block relative top-[1px]"
                                            onsubmit="return confirm('Bileti veritabanından kalıcı olarak silmek istediğinize emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-rose-600 transition-colors"
                                                title="Sil">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-slate-900">Kayıt Bulunamadı</h3>
                                    <p class="mt-1 text-sm text-slate-500">Arama kriterlerinize uyan bir destek bileti
                                        bulunmuyor.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($tickets->hasPages())
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-200">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
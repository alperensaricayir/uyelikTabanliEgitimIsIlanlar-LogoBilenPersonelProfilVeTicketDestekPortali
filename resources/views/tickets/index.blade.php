<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-neutral-900 leading-tight">Destek Ticket'ları</h2>
            <a href="{{ route('tickets.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-violet-600 text-white text-sm font-semibold shadow-sm hover:bg-violet-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Yeni Ticket
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-neutral-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div
                    class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white border border-neutral-200 shadow-sm rounded-2xl overflow-hidden">
                @if($tickets->isEmpty())
                    <div class="py-16 text-center">
                        <div class="w-16 h-16 rounded-full bg-neutral-100 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-neutral-900">Ticket Bulunamadı</h3>
                        <p class="text-sm text-neutral-500 mt-1">Henüz destek ticket'ı açmadınız.</p>
                        <a href="{{ route('tickets.create') }}"
                            class="mt-4 inline-flex px-4 py-2 text-sm font-semibold text-violet-600 bg-violet-50 hover:bg-violet-100 rounded-lg transition-colors">
                            İlk Ticket'ı Oluştur
                        </a>
                    </div>
                @else
                    <ul class="divide-y divide-neutral-100">
                        @foreach($tickets as $ticket)
                            <li
                                class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-neutral-50 transition-colors">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-1">
                                        <a href="{{ route('tickets.show', $ticket) }}"
                                            class="font-semibold text-neutral-900 hover:text-violet-600 transition-colors truncate text-base">
                                            #{{ $ticket->id }} – {{ $ticket->subject }}
                                        </a>
                                        @php
                                            $badgeMap = [
                                                'open' => ['bg-emerald-100 text-emerald-700', 'Açık'],
                                                'pending' => ['bg-blue-100 text-blue-700', 'Bekliyor'],
                                                'answered' => ['bg-violet-100 text-violet-700', 'Yanıtlandı'],
                                                'closed' => ['bg-neutral-100 text-neutral-600', 'Kapalı'],
                                            ];
                                            [$badgeCls, $badgeLabel] = $badgeMap[$ticket->status] ?? ['bg-neutral-100 text-neutral-600', $ticket->status];
                                        @endphp
                                        <span
                                            class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold {{ $badgeCls }} flex-shrink-0">
                                            {{ $badgeLabel }}
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-neutral-500">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Son güncel:
                                            {{ $ticket->last_reply_at ? $ticket->last_reply_at->diffForHumans() : $ticket->updated_at->diffForHumans() }}
                                        </span>
                                        @if($ticket->lastReplyBy)
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                Son yanıt: {{ $ticket->lastReplyBy->name }}
                                            </span>
                                        @endif
                                        @if(auth()->user()->isAgent())
                                            <span class="font-medium text-neutral-700">Oluşturan:
                                                {{ $ticket->user->name ?? '—' }}</span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('tickets.show', $ticket) }}"
                                    class="flex-shrink-0 inline-flex items-center justify-center px-4 py-2 border border-neutral-200 rounded-xl text-sm font-semibold text-neutral-700 hover:bg-neutral-100 hover:text-neutral-900 transition-colors">
                                    Görüntüle
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="mt-6">{{ $tickets->links() }}</div>
        </div>
    </div>
</x-app-layout>
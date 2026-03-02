@extends('cms.layouts.app')

@section('title', 'Ticket Detayı - #' . $ticket->id)

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                    Ticket #{{ $ticket->id }}: {{ $ticket->subject }}
                    <span
                        class="inline-flex px-2.5 py-1 rounded-md text-[11px] font-bold uppercase {{ $ticket->statusBadgeClass() }}">
                        {{ $ticket->statusLabel() }}
                    </span>
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Kullanıcı: {{ $ticket->user->name ?? 'Bilinmeyen' }} ({{ $ticket->user->email ?? 'E-posta Yok' }})
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('cms.tickets.index') }}"
                    class="px-4 py-2 border border-slate-300 text-slate-700 bg-white hover:bg-slate-50 rounded-lg text-sm font-medium transition-colors">
                    Listeye Dön
                </a>
                @if($ticket->status !== 'closed')
                    <form method="POST" action="{{ route('cms.tickets.close', $ticket) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="px-4 py-2 bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 rounded-lg text-sm font-medium transition-colors">
                            Ticket'ı Kapat
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div
                class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm font-medium flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="space-y-6">
                @foreach($ticket->replies as $reply)
                    @php
                        $isAgent = $reply->user && ($reply->user->isAdmin() || $reply->user->isEditor() || $reply->user->isAgent());
                    @endphp
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div
                                class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold {{ $isAgent ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600' }}">
                                {{ strtoupper(substr($reply->user->name ?? 'U', 0, 1)) }}
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-medium text-slate-500 mb-1">
                                {{ $reply->user->name ?? 'Bilinmeyen' }}
                                @if($isAgent) <span class="text-indigo-600">(Personel)</span> @endif
                                • {{ $reply->created_at->format('d M Y H:i') }}
                            </div>
                            <div
                                class="text-sm text-slate-800 bg-slate-50 border border-slate-100 p-4 rounded-xl whitespace-pre-wrap">
                                {!! nl2br(e($reply->message)) !!}

                                @if($reply->attachments->isNotEmpty())
                                    <div class="mt-4 flex flex-wrap gap-3">
                                        @foreach($reply->attachments as $attachment)
                                            <a href="{{ Storage::disk('public')->url($attachment->file_path) }}" target="_blank"
                                                class="block">
                                                <img src="{{ Storage::disk('public')->url($attachment->file_path) }}" alt="Ek"
                                                    class="max-w-[200px] max-h-[200px] object-cover rounded-lg border border-slate-200 hover:opacity-90 transition-opacity">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($ticket->status !== 'closed')
                <div class="pt-6 border-t border-slate-200 mt-8">
                    <form method="POST" action="{{ route('cms.tickets.reply', $ticket) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-800 mb-2">Ticket'a Yanıt Ver</label>
                            <textarea name="body" rows="4" required
                                class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                placeholder="Müşteriye iletilecek mesajı girin..."></textarea>
                            @error('body')
                                <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Ek Dosyalar (Opsiyonel)</label>
                            <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/webp,image/jpg"
                                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors cursor-pointer border border-slate-200 rounded-md">
                            @error('images.*')
                                <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
                                Yanıtı Gönder
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div
                    class="bg-slate-50 border border-slate-200 text-slate-500 rounded-xl p-6 text-center text-sm font-medium mt-8">
                    Bu ticket kapalı durumda olduğu için yeni yanıt eklenememektedir.
                </div>
            @endif
        </div>
    </div>
@endsection
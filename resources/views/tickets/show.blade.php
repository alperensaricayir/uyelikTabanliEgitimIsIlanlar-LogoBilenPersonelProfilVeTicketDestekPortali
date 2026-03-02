<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <h2 class="font-semibold text-xl text-neutral-900 dark:text-gray-100 leading-tight flex items-center gap-3">
                Ticket #{{ $ticket->id }}: {{ $ticket->subject }}
                <span
                    class="inline-flex px-2.5 py-1 rounded-md text-[11px] font-bold uppercase {{ $ticket->statusBadgeClass() }}">
                    {{ $ticket->statusLabel() }}
                </span>
            </h2>

            <div class="flex items-center gap-3">
                <a href="{{ route('tickets.index') }}"
                    class="text-sm font-semibold text-neutral-600 dark:text-gray-400 hover:text-neutral-900 dark:hover:text-gray-200 transition-colors">
                    ← Ticket'lara Dön
                </a>
                @if($ticket->status !== 'closed')
                    <form method="POST" action="{{ route('tickets.close', $ticket) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="px-4 py-2 border border-rose-200 text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-xl text-sm font-semibold transition-colors">
                            Kapat
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-neutral-50 dark:bg-gray-950 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div
                    class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Thread Timeline --}}
            <div class="space-y-6">
                @foreach($ticket->replies as $reply)
                    @php
                        $isMe = $reply->user_id === auth()->id();
                        $isAgent = $reply->user && ($reply->user->isAdmin() || $reply->user->isEditor() || $reply->user->isAgent());
                    @endphp
                    <div class="flex gap-4 {{ $isMe ? 'flex-row-reverse' : '' }}">
                        <div class="flex-shrink-0">
                            <div
                                class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold {{ $isAgent ? 'bg-violet-600 text-white' : 'bg-neutral-200 dark:bg-gray-700 text-neutral-700 dark:text-gray-200' }}">
                                {{ strtoupper(substr($reply->user->name ?? 'U', 0, 1)) }}
                            </div>
                        </div>
                        <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }} max-w-[85%]">
                            <span class="text-xs font-semibold text-neutral-500 dark:text-gray-400 mb-1 mx-1">
                                {{ $reply->user->name ?? 'Bilinmeyen' }}
                                @if($isAgent) <span class="text-violet-600">(Destek)</span> @endif
                                • {{ $reply->created_at->format('d M Y H:i') }}
                            </span>
                            <div
                                class="bg-white dark:bg-gray-900 border dark:border-gray-800 text-sm text-neutral-800 dark:text-gray-200 p-4 shadow-sm whitespace-pre-wrap {{ $isMe ? 'rounded-2xl rounded-tr-sm border-neutral-200' : 'rounded-2xl rounded-tl-sm border-violet-100 dark:border-violet-900/30 ring-1 ring-violet-50 dark:ring-violet-900/20' }}">
                                {!! nl2br(e($reply->message)) !!}

                                @if($reply->attachments->isNotEmpty())
                                    <div class="mt-4 flex flex-wrap gap-3">
                                        @foreach($reply->attachments as $attachment)
                                            <a href="{{ Storage::disk('public')->url($attachment->file_path) }}" target="_blank"
                                                class="block">
                                                <img src="{{ Storage::disk('public')->url($attachment->file_path) }}" alt="Ek"
                                                    class="max-w-[200px] max-h-[200px] object-cover rounded-xl border border-neutral-200 dark:border-gray-700 hover:opacity-90 transition-opacity">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Reply Form --}}
            <div class="pt-4 border-t border-neutral-200 dark:border-gray-800 mt-8">
                @if($ticket->status !== 'closed')
                    <div
                        class="bg-white dark:bg-gray-900 border border-neutral-200 dark:border-gray-800 shadow-sm rounded-2xl p-6">
                        <h3 class="font-semibold text-neutral-900 dark:text-gray-100 mb-4">Yeni Yanıt Ekle</h3>
                        <form method="POST" action="{{ route('tickets.reply', $ticket) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <textarea name="message" rows="4" required
                                    class="w-full border-neutral-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-xl shadow-sm focus:ring-violet-500 focus:border-violet-500 text-sm"
                                    placeholder="Yanıtınızı buraya yazın..."></textarea>
                                @error('message')
                                    <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-neutral-900 dark:text-gray-200 mb-1.5">Ekler
                                    (Görsel, Max:
                                    5MB)</label>
                                <input type="file" name="images[]" multiple
                                    accept="image/jpeg,image/png,image/webp,image/jpg"
                                    class="block w-full text-sm text-neutral-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 dark:file:bg-violet-900/30 dark:file:text-violet-400 hover:file:bg-violet-100 transition-colors cursor-pointer border border-neutral-200 dark:border-gray-700 rounded-lg">
                                @error('images.*')
                                    <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="bg-violet-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-violet-700 transition">
                                    Yanıt Gönder
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div
                        class="bg-neutral-100 dark:bg-gray-900 border border-neutral-200 dark:border-gray-800 text-neutral-500 dark:text-gray-400 rounded-2xl p-6 text-center text-sm font-medium">
                        <svg class="w-8 h-8 mx-auto mb-2 text-neutral-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Bu destek ticket'ı kapatılmış. Yeni bir sorun için lütfen yeni ticket açın.
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
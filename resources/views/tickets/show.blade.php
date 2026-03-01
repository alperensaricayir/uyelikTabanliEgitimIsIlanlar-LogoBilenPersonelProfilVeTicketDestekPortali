<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bilet #{{ $ticket->id }}: {{ $ticket->subject }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- Konuşma geçmişi --}}
            @foreach($ticket->replies as $reply)
                <div
                    class="bg-white shadow rounded-lg p-5 {{ $reply->user_id === auth()->id() ? 'ml-8 border-l-4 border-indigo-400' : '' }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-semibold text-gray-800">{{ $reply->user->name }}</span>
                        <span class="text-xs text-gray-400">{{ $reply->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $reply->message }}</p>
                </div>
            @endforeach

            {{-- Yanıt Formu --}}
            @if($ticket->status !== 'closed')
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="font-semibold text-gray-700 mb-3">Yanıt Ekle</h3>
                    <form method="POST" action="{{ route('tickets.reply', $ticket) }}">
                        @csrf
                        @if(session('success'))
                            <div class="mb-3 text-green-600 text-sm">{{ session('success') }}</div>
                        @endif
                        <textarea name="message" rows="4" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mb-3"
                            placeholder="Yanıtınızı yazın..."></textarea>
                        <button type="submit"
                            class="bg-indigo-600 text-white px-5 py-2 rounded hover:bg-indigo-700 transition text-sm">
                            Yanıtla
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-gray-100 text-gray-500 rounded-lg p-4 text-center text-sm">
                    Bu bilet kapatılmış. Yeni bir sorun için lütfen yeni bilet açın.
                </div>
            @endif

            <a href="{{ route('tickets.index') }}" class="inline-block text-indigo-600 hover:underline text-sm">
                ← Biletlerime Dön
            </a>
        </div>
    </div>
</x-app-layout>
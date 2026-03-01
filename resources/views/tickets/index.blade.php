<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Destek Biletleri</h2>
            <a href="{{ route('tickets.create') }}"
                class="bg-indigo-600 text-white px-4 py-2 rounded text-sm hover:bg-indigo-700 transition">
                + Yeni Bilet
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow rounded-lg divide-y">
                @forelse($tickets as $ticket)
                    <div class="p-5 flex items-center justify-between hover:bg-gray-50 transition">
                        <div>
                            <a href="{{ route('tickets.show', $ticket) }}"
                                class="font-medium text-indigo-700 hover:underline">
                                #{{ $ticket->id }} – {{ $ticket->subject }}
                            </a>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ $ticket->created_at->diffForHumans() }}
                                @if(auth()->user()->isAgent())
                                    &nbsp;·&nbsp; <span class="font-medium">{{ $ticket->user->name ?? '—' }}</span>
                                @endif
                            </p>
                        </div>
                        <span
                            class="text-xs px-2 py-1 rounded-full font-semibold
                                {{ $ticket->status === 'open' ? 'bg-green-100 text-green-700' : ($ticket->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500') }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-12">Henüz bilet bulunmuyor.</p>
                @endforelse
            </div>

            <div class="mt-6">{{ $tickets->links() }}</div>
        </div>
    </div>
</x-app-layout>
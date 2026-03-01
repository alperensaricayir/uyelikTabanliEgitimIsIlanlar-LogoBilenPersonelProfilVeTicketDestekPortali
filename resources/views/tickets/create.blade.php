<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Yeni Destek Bileti</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-8">
                <form method="POST" action="{{ route('tickets.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konu</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Sorunuzu kısaca yazın">
                        @error('subject')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mesajınız</label>
                        <textarea name="message" rows="6" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Sorununuzu detaylı açıklayın...">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition">
                            Bileti Gönder
                        </button>
                        <a href="{{ route('tickets.index') }}"
                            class="px-6 py-2 rounded border border-gray-300 text-gray-600 hover:bg-gray-50 transition">
                            İptal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
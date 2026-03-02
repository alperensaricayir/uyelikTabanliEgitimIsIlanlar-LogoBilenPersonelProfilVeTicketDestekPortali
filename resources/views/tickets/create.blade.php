<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-900 dark:text-gray-100 leading-tight">Yeni Destek Ticket'ı Oluştur
        </h2>
    </x-slot>

    <div class="py-12 bg-neutral-50 dark:bg-gray-950 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-900 border border-neutral-200 dark:border-gray-800 shadow-sm rounded-2xl p-6 sm:p-8">
                <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-5">
                        <label
                            class="block text-sm font-semibold text-neutral-900 dark:text-gray-200 mb-1.5">Konu</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required
                            class="w-full border-neutral-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-xl shadow-sm focus:ring-violet-500 focus:border-violet-500 text-sm"
                            placeholder="Örn: Eğitime erişemiyorum">
                        @error('subject')
                            <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-neutral-900 dark:text-gray-200 mb-1.5">Öncelik
                            (İsteğe
                            Bağlı)</label>
                        <select name="priority"
                            class="w-full border-neutral-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-xl shadow-sm focus:ring-violet-500 focus:border-violet-500 text-sm">
                            <option value="medium">Normal</option>
                            <option value="low">Düşük</option>
                            <option value="high">Yüksek</option>
                        </select>
                        @error('priority')
                            <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-8">
                        <label
                            class="block text-sm font-semibold text-neutral-900 dark:text-gray-200 mb-1.5">Mesajınız</label>
                        <textarea name="message" rows="6" required
                            class="w-full border-neutral-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 rounded-xl shadow-sm focus:ring-violet-500 focus:border-violet-500 text-sm"
                            placeholder="Sorununuzu detaylı bir şekilde açıklayın...">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-neutral-900 dark:text-gray-200 mb-1.5">Ekler
                            (İsteğe Bağlı, Görsel, Max: 5MB)</label>
                        <input type="file" name="images[]" multiple accept="image/jpeg,image/png,image/webp,image/jpg"
                            class="block w-full text-sm text-neutral-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 dark:file:bg-violet-900/30 dark:file:text-violet-400 hover:file:bg-violet-100 transition-colors cursor-pointer border border-neutral-200 dark:border-gray-700 rounded-xl">
                        @error('images.*')
                            <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit"
                            class="bg-violet-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-violet-700 focus:ring-4 focus:ring-violet-100 transition-all">
                            Gönder
                        </button>
                        <a href="{{ route('tickets.index') }}"
                            class="px-6 py-2.5 rounded-xl border border-neutral-200 dark:border-gray-700 text-neutral-700 dark:text-gray-300 text-sm font-semibold hover:bg-neutral-50 dark:hover:bg-gray-800 transition-colors">
                            İptal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
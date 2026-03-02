<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ $training->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 border dark:border-gray-800 shadow rounded-lg p-8">

                @if($training->is_premium_only)
                    <span
                        class="inline-block mb-4 text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 px-3 py-1 rounded-full">
                        ⭐ Premium Eğitim
                    </span>
                @endif

                <p class="text-gray-700 dark:text-gray-300 mb-8 leading-relaxed">{{ $training->description }}</p>

                <div class="mb-8">
                    <button type="button"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg shadow transition-colors inline-block cursor-not-allowed opacity-80"
                        onclick="alert('Yakında eğitimlere kayıt sistemi aktif edilecektir.')">
                        🎓 Eğitime Kayıt Ol
                    </button>
                </div>

                {{-- GATED CONTENT --}}
                @if($training->meeting_url || $training->resources_url)
                    <div class="border-t dark:border-gray-800 pt-6">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-4">📎 Eğitim Materyalleri</h3>

                        @if($canViewGated)
                            {{-- Premium üye veya admin görebilir --}}
                            @if($training->meeting_url)
                                <a href="{{ $training->meeting_url }}" target="_blank"
                                    class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition mr-3 mb-2">
                                    🎥 Toplantı Linki
                                </a>
                            @endif
                            @if($training->resources_url)
                                <a href="{{ $training->resources_url }}" target="_blank"
                                    class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition mb-2">
                                    📚 Kaynaklar
                                </a>
                            @endif
                        @else
                            {{-- Kilitli içerik görünümü --}}
                            <div
                                class="bg-gray-50 dark:bg-gray-800/50 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg p-6 text-center">
                                <div class="text-4xl mb-3">🔒</div>
                                <p class="text-gray-600 dark:text-gray-300 font-medium mb-2">Bu içerik Premium üyelere özeldir.
                                </p>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Eğitim linki ve kaynaklara erişmek için
                                    Premium üyeliğe
                                    ihtiyacınız var.</p>
                                @guest
                                    <a href="{{ route('login') }}"
                                        class="inline-block mt-4 bg-indigo-600 text-white px-5 py-2 rounded hover:bg-indigo-700 transition">
                                        Giriş Yap
                                    </a>
                                @endguest
                            </div>
                        @endif
                    </div>
                @endif

                <div class="mt-6">
                    <a href="{{ route('trainings.index') }}" class="text-indigo-600 hover:underline text-sm">
                        ← Tüm Eğitimlere Dön
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
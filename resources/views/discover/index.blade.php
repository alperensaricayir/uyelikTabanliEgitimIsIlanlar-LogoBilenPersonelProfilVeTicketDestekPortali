<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">Discover – Öne Çıkanlar</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">
                Üyeler beğeni sayısı, premium durumu ve öne çıkan bağlantılarına göre sıralanmaktadır.
            </p>

            <div class="space-y-4">
                @forelse($members as $index => $member)
                    <div
                        class="bg-white dark:bg-gray-900 border dark:border-gray-800 shadow rounded-lg p-5 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            {{-- Sıralama numarası --}}
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm
                                        {{ $index === 0 ? 'bg-yellow-400 text-white' : ($index === 1 ? 'bg-gray-300 text-gray-700' : ($index === 2 ? 'bg-amber-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400')) }}">
                                {{ $members->firstItem() + $index }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $member->name }}</p>
                                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    @if($member->is_premium)
                                        <span
                                            class="bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 px-1.5 py-0.5 rounded">⭐
                                            Premium</span>
                                    @endif
                                    @if($member->featured_links)
                                        <span
                                            class="bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 px-1.5 py-0.5 rounded">🔗
                                            Featured</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            {{-- Skor --}}
                            <div class="text-center">
                                <p class="text-2xl font-bold text-indigo-600">{{ round($member->discover_score) }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">Puan</p>
                            </div>

                            {{-- Like Butonu --}}
                            @auth
                                <button onclick="likeUser({{ $member->id }}, this)"
                                    class="flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400 hover:text-red-500 transition">
                                    ❤ <span>{{ $member->likes_count }}</span>
                                </button>
                            @endauth
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 dark:text-gray-500 py-12">Henüz üye bulunmuyor.</p>
                @endforelse
            </div>

            <div class="mt-6">{{ $members->links() }}</div>
        </div>
    </div>

    {{-- Inline AJAX Like --}}
    <script>
        async function likeUser(userId, btn) {
            const resp = await fetch('/api/v1/likes', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-XSRF-TOKEN': decodeURIComponent(document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] || ''),
                },
                body: JSON.stringify({ likeable_type: 'App\\Models\\User', likeable_id: userId }),
            });

            const data = await resp.json();
            if (resp.ok) {
                const counter = btn.querySelector('span');
                counter.textContent = parseInt(counter.textContent) + 1;
                btn.classList.add('text-red-500');
            } else {
                alert(data.message);
            }
        }
    </script>
</x-app-layout>
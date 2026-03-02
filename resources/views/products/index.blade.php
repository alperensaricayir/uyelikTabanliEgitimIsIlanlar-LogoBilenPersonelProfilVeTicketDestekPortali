<x-app-layout>
    <div class="py-12 bg-neutral-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-28">
                <h1 class="text-3xl font-extrabold text-neutral-900 dark:text-white tracking-tight sm:text-4xl">
                    Ürünlerimiz
                </h1>
                <p class="mt-4 max-w-2xl mx-auto text-lg text-neutral-500 dark:text-gray-400">
                    Sitemizde yer alan tüm ürün, hizmet ve projelere göz atabilirsiniz.
                </p>
            </div>

            @if($products->isEmpty())
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-12 text-center shadow-sm border border-neutral-200 dark:border-gray-700">
                    <div
                        class="w-16 h-16 bg-neutral-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-neutral-900 dark:text-white mb-2">Henüz Ürün Eklenmemiş</h3>
                    <p class="text-neutral-500 dark:text-gray-400">Şu anda listelenecek herhangi bir ürün bulunmamaktadır.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($products as $product)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-neutral-200 dark:border-gray-700 overflow-hidden flex flex-col transition-transform hover:-translate-y-1 hover:shadow-md">
                            @if($product->image_path)
                                <img src="{{ Storage::disk('public')->url($product->image_path) }}" alt="{{ $product->title }}"
                                    class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-neutral-100 dark:bg-gray-700 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-neutral-300 dark:text-gray-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            <div class="p-6 flex-1 flex flex-col">
                                <h3 class="text-xl font-bold text-neutral-900 dark:text-white mb-2">{{ $product->title }}</h3>
                                <div
                                    class="text-neutral-600 dark:text-gray-400 text-sm mb-6 flex-1 prose prose-sm dark:prose-invert">
                                    {!! $product->description !!}
                                </div>
                                <div
                                    class="flex items-center justify-between mt-auto pt-4 border-t border-neutral-100 dark:border-gray-700">
                                    <div>
                                        @if(empty($product->price) || $product->price == 0)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                                                Ücretsiz
                                            </span>
                                        @else
                                            <span class="text-lg font-bold text-neutral-900 dark:text-white">
                                                ₺{{ number_format($product->price, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($product->url)
                                        <a href="{{ $product->url }}" target="_blank" rel="noopener noreferrer"
                                            class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white {{ (empty($product->price) || $product->price == 0) ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-rose-600 hover:bg-rose-700' }} transition-colors">
                                            {{ (empty($product->price) || $product->price == 0) ? 'İncele' : 'Satın Al' }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
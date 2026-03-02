@extends('cms.layouts.app')

@section('title', 'Kullanıcı Yönetimi')

@section('content')
    <div class="space-y-6">

        {{-- Header & Actions --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Sistem Kullanıcıları</h2>
                <p class="text-sm text-slate-500 mt-1">Sistemdeki tüm üyeleri ve yöneticileri buradan yönetebilirsiniz.</p>
            </div>
            <a href="{{ route('cms.users.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Yeni Kullanıcı Ekle
            </a>
        </div>

        {{-- Filters & Search --}}
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <form method="GET" action="{{ route('cms.users.index') }}"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- Search Box --}}
                <div class="lg:col-span-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="İsim veya e-posta ile ara..."
                        class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                {{-- Role Filter --}}
                <div>
                    <select name="role"
                        class="block w-full py-2 pl-3 pr-10 border border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Tüm Roller</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Yönetici (Admin)</option>
                        <option value="agent" {{ request('role') == 'agent' ? 'selected' : '' }}>Temsilci (Agent)</option>
                        <option value="member" {{ request('role') == 'member' ? 'selected' : '' }}>Üye (Member)</option>
                    </select>
                </div>

                {{-- Status Filter --}}
                <div class="flex gap-2">
                    <select name="is_active"
                        class="block w-full py-2 pl-3 pr-10 border border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Tüm Durumlar</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Pasif</option>
                    </select>
                    <button type="submit"
                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition-colors border border-slate-300">
                        Filtrele
                    </button>
                </div>
            </form>
        </div>

        {{-- Users Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Kullanıcı
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Rol Kimliği
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Durum
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Kayıt Tarihi
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                İşlemler
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-slate-900">{{ $user->name }}</div>
                                            <div class="text-sm text-slate-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->role === 'admin')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Yönetici
                                        </span>
                                    @elseif($user->role === 'agent')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Temsilci
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                            Üye
                                        </span>
                                    @endif
                                    @if($user->is_premium)
                                        <span
                                            class="ml-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            Premium
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->is_active)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">
                                            Pasif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-3">
                                        {{-- Edit Button --}}
                                        <a href="{{ route('cms.users.edit', $user) }}"
                                            class="text-indigo-600 hover:text-indigo-900 font-semibold" title="Düzenle">
                                            Düzenle
                                        </a>

                                        <span class="text-slate-300">|</span>

                                        {{-- Toggle Admin --}}
                                        @if(auth()->id() !== $user->id)
                                            <form method="POST" action="{{ route('cms.users.toggleAdmin', $user) }}"
                                                class="inline-block"
                                                onsubmit="return confirm('Bu kullanıcının yetki düzeyini değiştirmek istediğinize emin misiniz?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="{{ $user->role === 'admin' ? 'text-amber-600 hover:text-amber-900' : 'text-emerald-600 hover:text-emerald-900' }} font-semibold text-xs uppercase"
                                                    title="Yönetici Yetkisini Aç/Kapat">
                                                    {{ $user->role === 'admin' ? '-Yönetici' : '+Yönetici' }}
                                                </button>
                                            </form>

                                            {{-- Toggle Active --}}
                                            <form method="POST" action="{{ route('cms.users.toggleActive', $user) }}"
                                                class="inline-block"
                                                onsubmit="return confirm('Bu kullanıcının erişim durumunu değiştirmek istediğinize emin misiniz?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="{{ $user->is_active ? 'text-rose-600 hover:text-rose-900' : 'text-blue-600 hover:text-blue-900' }} font-semibold text-xs uppercase"
                                                    title="Hesabı Pasife/Aktife Al">
                                                    {{ $user->is_active ? 'Pasife Al' : 'Aktif Et' }}
                                                </button>
                                            </form>

                                            {{-- Delete --}}
                                            <form method="POST" action="{{ route('cms.users.destroy', $user) }}"
                                                class="inline-block relative top-[1px]"
                                                onsubmit="return confirm('Kullanıcıyı kalıcı olarak silmek istediğinize emin misiniz? Bu işlem geri alınamaz.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-slate-400 hover:text-rose-600 transition-colors"
                                                    title="Sil">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-slate-400 italic">Geçerli Kullanıcı</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-slate-900">Kayıt Bulunamadı</h3>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Arama kriterlerinize uyan bir kullanıcı bulunmuyor veya sistemde tanımlı kullanıcı yok.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-200">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
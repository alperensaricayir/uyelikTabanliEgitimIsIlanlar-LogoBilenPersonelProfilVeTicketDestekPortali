@extends('cms.layouts.app')

@section('title', 'Yeni Kullanıcı Oluştur')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Sisteme Yeni Kullanıcı Ekle</h2>
                <p class="text-sm text-slate-500 mt-1">Gerekli hesap bilgilerini ve yetki kurallarını aşağıdan yapılandırın.
                </p>
            </div>
            <a href="{{ route('cms.users.index') }}"
                class="text-sm font-medium text-slate-500 hover:text-slate-800 transition-colors">
                &larr; Kullanıcılara Dön
            </a>
        </div>

        {{-- Form --}}
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <form action="{{ route('cms.users.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Ad Soyad --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Ad Soyad</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('name')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- E-posta --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">E-posta Adresi</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('email')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Şifre --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Şifre</label>
                        <input type="password" name="password" id="password" required minlength="8"
                            class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <p class="mt-1 text-xs text-slate-400">En az 8 karakter olmalıdır.</p>
                        @error('password')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="role" class="block text-sm font-medium text-slate-700 mb-1">Hesap Rolü</label>
                        <select name="role" id="role" required
                            class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="member" {{ old('role') == 'member' ? 'selected' : '' }}>Standart Üye (Member)
                            </option>
                            <option value="agent" {{ old('role') == 'agent' ? 'selected' : '' }}>Destek Temsilcisi (Agent)
                            </option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Sistem Yöneticisi (Admin)
                            </option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Toggles --}}
                <div class="border-t border-slate-200 pt-6">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-slate-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_active" class="font-medium text-slate-700">Hesap Aktif</label>
                            <p class="text-slate-500">Kullanıcının sisteme giriş yapabilmesi için işaretli bırakın.</p>
                        </div>
                    </div>
                    @error('is_active')
                        <p class="mt-1 text-sm text-rose-600 mb-4">{{ $message }}</p>
                    @enderror

                    <div class="flex items-start mt-4">
                        <div class="flex items-center h-5">
                            <input id="is_premium" name="is_premium" type="checkbox" value="1" {{ old('is_premium', false) ? 'checked' : '' }}
                                class="focus:ring-amber-500 h-4 w-4 text-amber-600 border-slate-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_premium" class="font-medium text-slate-700 flex items-center gap-1">⭐ Premium
                                Üye</label>
                            <p class="text-slate-500">Kullanıcıya özel eğitim içeriklerine sınırsız erişim yetkisi verir.
                            </p>
                        </div>
                    </div>
                    @error('is_premium')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('cms.users.index') }}"
                        class="px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                        İptal
                    </a>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Kullanıcıyı Kaydet
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection
{{-- Shared form fields for create & edit --}}

@php $isEdit = isset($course) && $course !== null; @endphp

{{-- Validation errors --}}
@if($errors->any())
    <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">

    {{-- Title --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Başlık <span class="text-red-500">*</span></label>
        <input type="text" name="title" value="{{ old('title', $course?->title) }}" required
            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('title') border-red-400 @enderror">
        @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Slug --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Slug <span class="text-gray-400 text-xs">(boş
                bırakılırsa otomatik oluşturulur)</span></label>
        <input type="text" name="slug" value="{{ old('slug', $course?->slug) }}"
            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-mono focus:ring-2 focus:ring-indigo-500 @error('slug') border-red-400 @enderror">
        @error('slug') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Description --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Açıklama <span class="text-red-500">*</span></label>
        <textarea name="description" rows="5" required
            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 @error('description') border-red-400 @enderror">{{ old('description', $course?->description) }}</textarea>
        @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Status + Premium row --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Durum <span
                    class="text-red-500">*</span></label>
            <select name="status"
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-indigo-500">
                @foreach(\App\Enums\ContentStatus::cases() as $s)
                    <option value="{{ $s->value }}" @selected(old('status', $course?->status?->value) === $s->value)>
                        {{ $s->label() }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Yayımlanma Tarihi</label>
            <input type="datetime-local" name="published_at"
                value="{{ old('published_at', $course?->published_at?->format('Y-m-d\TH:i')) }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-indigo-500">
        </div>
    </div>

    {{-- URLs --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Toplantı URL</label>
            <input type="url" name="meeting_url" value="{{ old('meeting_url', $course?->meeting_url) }}"
                placeholder="https://"
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kaynaklar URL</label>
            <input type="url" name="resources_url" value="{{ old('resources_url', $course?->resources_url) }}"
                placeholder="https://"
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-indigo-500">
        </div>
    </div>

    {{-- Thumbnail --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Kapak Görseli</label>
        @if($isEdit && $course->thumbnail)
            <div class="mb-3 flex items-center gap-4">
                <img src="{{ $course->thumbnailUrl() }}" class="w-24 h-16 object-cover rounded-lg border border-gray-200">
                <label class="flex items-center gap-2 text-sm text-red-600 cursor-pointer">
                    <input type="checkbox" name="remove_thumbnail" value="1" class="rounded border-gray-300">
                    Görseli Kaldır
                </label>
            </div>
        @endif
        <input type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp"
            class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
        <p class="mt-1 text-xs text-gray-400">JPG, PNG veya WebP · Maks 2MB</p>
        @error('thumbnail') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Premium toggle --}}
    <div class="flex items-center gap-3 pt-1">
        <input type="hidden" name="is_premium_only" value="0">
        <input type="checkbox" name="is_premium_only" id="isPremium" value="1" @checked(old('is_premium_only', $course?->is_premium_only)) class="rounded border-gray-300 text-amber-500 focus:ring-amber-400 w-4 h-4">
        <label for="isPremium" class="text-sm font-medium text-gray-700">
            ⭐ Sadece Premium Üyelere Açık
        </label>
    </div>
</div>
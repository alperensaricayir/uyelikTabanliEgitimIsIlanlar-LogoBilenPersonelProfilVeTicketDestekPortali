{{-- Shared lesson form fields for create & edit --}}

@if($errors->any())
    <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">

    {{-- Title + Slug --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Başlık <span
                    class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title', $lesson?->title) }}" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 @error('title') border-red-400 @enderror">
            @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $lesson?->slug) }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-mono focus:ring-indigo-500">
        </div>
    </div>

    {{-- Content (rich textarea) --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">İçerik</label>
        <p class="text-xs text-gray-400 mb-1">HTML desteklenmektedir. Güvenli etiketler: &lt;b&gt;, &lt;i&gt;,
            &lt;ul&gt;, &lt;ol&gt;, &lt;li&gt;, &lt;p&gt;, &lt;h2&gt;–&lt;h4&gt;, &lt;a&gt;, &lt;img&gt;, vb.</p>
        <textarea name="content" id="lessonContent" rows="14"
            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm font-mono focus:ring-2 focus:ring-indigo-500 @error('content') border-red-400 @enderror">{{ old('content', $lesson?->content) }}</textarea>
        @error('content') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Video URL --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Video URL</label>
        <input type="url" name="video_url" value="{{ old('video_url', $lesson?->video_url) }}"
            placeholder="https://youtube.com/..."
            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-indigo-500 @error('video_url') border-red-400 @enderror">
        @error('video_url') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    {{-- Status + Sort order + Published at --}}
    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Durum <span
                    class="text-red-500">*</span></label>
            <select name="status"
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-indigo-500">
                @foreach(\App\Enums\ContentStatus::cases() as $s)
                    <option value="{{ $s->value }}" @selected(old('status', $lesson?->status?->value ?? 'draft') === $s->value)>{{ $s->label() }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Sıra</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $lesson?->sort_order ?? 0) }}" min="0"
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Yayımlanma Tarihi</label>
            <input type="datetime-local" name="published_at"
                value="{{ old('published_at', $lesson?->published_at?->format('Y-m-d\TH:i')) }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-indigo-500">
        </div>
    </div>

    {{-- Preview toggle --}}
    <div class="flex items-center gap-3">
        <input type="hidden" name="is_preview" value="0">
        <input type="checkbox" name="is_preview" id="isPreview" value="1" @checked(old('is_preview', $lesson?->is_preview)) class="rounded border-gray-300 text-blue-500 focus:ring-blue-400 w-4 h-4">
        <label for="isPreview" class="text-sm font-medium text-gray-700">
            👁 Önizleme Dersi (kayıt yaptırmadan görüntülenebilir)
        </label>
    </div>
</div>
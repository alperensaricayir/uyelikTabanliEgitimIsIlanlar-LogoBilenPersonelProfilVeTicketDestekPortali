<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Edit Profile') }}
            </h2>
            <a href="{{ route('profile.show') }}"
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                Cancel
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if(session('status') === 'profile-updated')
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                    class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Profile updated successfully.
                </div>
            @endif

            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data"
                class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border border-gray-100 dark:border-gray-700 p-6 md:p-10 space-y-8">
                @csrf
                @method('patch')

                <!-- Profile Photo -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-200 mb-4">Profile Photo</h3>
                    <div class="flex items-center gap-6 text-sm">
                        <div
                            class="relative w-24 h-24 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 shrink-0">
                            @if($user->profile_photo_path)
                                <img id="photo-preview" src="{{ asset('storage/' . $user->profile_photo_path) }}"
                                    class="w-full h-full object-cover">
                            @else
                                <img id="photo-preview" src="" class="w-full h-full object-cover hidden">
                                <div id="photo-placeholder"
                                    class="w-full h-full flex items-center justify-center text-gray-400 dark:text-gray-600">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <label for="profile_photo"
                                class="inline-block px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg cursor-pointer transition shadow-sm text-sm">
                                Dosya Seç
                            </label>
                            <span id="file-name" class="ml-3 text-gray-500 dark:text-gray-400">Dosya seçilmedi</span>
                            <input type="file" id="profile_photo" name="profile_photo"
                                accept="image/jpeg, image/png, image/webp" class="hidden"
                                onchange="previewImage(event)">
                            <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">PNG, JPG, WEBP up to 2MB</p>
                            <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100 dark:border-gray-700">

                <!-- Essential Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text"
                            class="mt-1 block w-full bg-gray-50 border-gray-200 focus:bg-white focus:border-indigo-500 dark:bg-gray-900/50 dark:border-gray-700 dark:focus:border-indigo-500"
                            :value="old('name', $user->name)" required autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" type="email"
                            class="mt-1 block w-full bg-gray-50 border-gray-200 focus:bg-white focus:border-indigo-500 dark:bg-gray-900/50 dark:border-gray-700 dark:focus:border-indigo-500"
                            :value="old('email', $user->email)" required autocomplete="username" />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>
                    <div>
                        <x-input-label for="headline" :value="__('Headline')" />
                        <x-text-input id="headline" name="headline" type="text"
                            class="mt-1 block w-full bg-gray-50 border-gray-200 focus:bg-white focus:border-indigo-500 dark:bg-gray-900/50 dark:border-gray-700 dark:focus:border-indigo-500"
                            :value="old('headline', $user->headline)" placeholder="e.g. Senior Software Engineer"
                            maxlength="120" />
                        <p class="mt-1 text-xs text-gray-400">Max 120 characters</p>
                        <x-input-error class="mt-2" :messages="$errors->get('headline')" />
                    </div>
                    <div>
                        <x-input-label for="city" :value="__('City')" />
                        <x-text-input id="city" name="city" type="text"
                            class="mt-1 block w-full bg-gray-50 border-gray-200 focus:bg-white focus:border-indigo-500 dark:bg-gray-900/50 dark:border-gray-700 dark:focus:border-indigo-500"
                            :value="old('city', $user->city)" />
                        <x-input-error class="mt-2" :messages="$errors->get('city')" />
                    </div>
                    <div>
                        <x-input-label for="country" :value="__('Country')" />
                        <x-text-input id="country" name="country" type="text"
                            class="mt-1 block w-full bg-gray-50 border-gray-200 focus:bg-white focus:border-indigo-500 dark:bg-gray-900/50 dark:border-gray-700 dark:focus:border-indigo-500"
                            :value="old('country', $user->country)" />
                        <x-input-error class="mt-2" :messages="$errors->get('country')" />
                    </div>
                    <div>
                        <x-input-label for="website_url" :value="__('Website')" />
                        <x-text-input id="website_url" name="website_url" type="url"
                            class="mt-1 block w-full bg-gray-50 border-gray-200 focus:bg-white focus:border-indigo-500 dark:bg-gray-900/50 dark:border-gray-700 dark:focus:border-indigo-500"
                            :value="old('website_url', $user->website_url)" placeholder="https://..." />
                        <x-input-error class="mt-2" :messages="$errors->get('website_url')" />
                    </div>
                </div>

                <!-- Bio -->
                <div>
                    <x-input-label for="bio" :value="__('Bio')" />
                    <textarea id="bio" name="bio" rows="4"
                        class="mt-1 block w-full rounded-md shadow-sm bg-gray-50 border-gray-200 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900/50 dark:border-gray-700 dark:focus:border-indigo-500 dark:focus:ring-indigo-500 dark:text-gray-100 sm:text-sm transition-colors duration-200">{{ old('bio', $user->bio) }}</textarea>
                    <p class="mt-1 text-xs text-gray-400">Max 2000 characters</p>
                    <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                </div>

                <!-- Skills -->
                <div>
                    <x-input-label for="skills" :value="__('Skills')" />
                    <x-text-input id="skills" name="skills" type="text"
                        class="mt-1 block w-full bg-gray-50 border-gray-200 focus:bg-white focus:border-indigo-500 dark:bg-gray-900/50 dark:border-gray-700 dark:focus:border-indigo-500"
                        :value="old('skills', $user->skills ? implode(', ', $user->skills) : '')"
                        placeholder="Laravel, PHP, Web Development" />
                    <p class="mt-1 text-xs text-gray-400">Separate skills with commas. Max 20 skills.</p>
                    <x-input-error class="mt-2" :messages="$errors->get('skills')" />
                </div>

                <hr class="border-gray-100 dark:border-gray-700">

                <!-- Social Links -->
                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-200 mb-4">Social Profiles</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="linkedin_url" :value="__('LinkedIn')" />
                        <x-text-input id="linkedin_url" name="linkedin_url" type="url"
                            class="mt-1 block w-full bg-gray-50 border-gray-200 focus:bg-white focus:border-indigo-500 dark:bg-gray-900/50 dark:border-gray-700 dark:focus:border-indigo-500"
                            :value="old('linkedin_url', $user->linkedin_url)"
                            placeholder="https://linkedin.com/in/..." />
                        <x-input-error class="mt-2" :messages="$errors->get('linkedin_url')" />
                    </div>
                    <div>
                        <x-input-label for="github_url" :value="__('GitHub')" />
                        <x-text-input id="github_url" name="github_url" type="url"
                            class="mt-1 block w-full bg-gray-50 border-gray-200 focus:bg-white focus:border-indigo-500 dark:bg-gray-900/50 dark:border-gray-700 dark:focus:border-indigo-500"
                            :value="old('github_url', $user->github_url)" placeholder="https://github.com/..." />
                        <x-input-error class="mt-2" :messages="$errors->get('github_url')" />
                    </div>
                    <div>
                        <x-input-label for="instagram_url" :value="__('Instagram')" />
                        <x-text-input id="instagram_url" name="instagram_url" type="url"
                            class="mt-1 block w-full bg-gray-50 border-gray-200 focus:bg-white focus:border-indigo-500 dark:bg-gray-900/50 dark:border-gray-700 dark:focus:border-indigo-500"
                            :value="old('instagram_url', $user->instagram_url)"
                            placeholder="https://instagram.com/..." />
                        <x-input-error class="mt-2" :messages="$errors->get('instagram_url')" />
                    </div>
                    <div>
                        <x-input-label for="youtube_url" :value="__('YouTube')" />
                        <x-text-input id="youtube_url" name="youtube_url" type="url"
                            class="mt-1 block w-full bg-gray-50 border-gray-200 focus:bg-white focus:border-indigo-500 dark:bg-gray-900/50 dark:border-gray-700 dark:focus:border-indigo-500"
                            :value="old('youtube_url', $user->youtube_url)" placeholder="https://youtube.com/..." />
                        <x-input-error class="mt-2" :messages="$errors->get('youtube_url')" />
                    </div>
                    <div>
                        <x-input-label for="twitter_url" :value="__('X (Twitter)')" />
                        <x-text-input id="twitter_url" name="twitter_url" type="url"
                            class="mt-1 block w-full bg-gray-50 border-gray-200 focus:bg-white focus:border-indigo-500 dark:bg-gray-900/50 dark:border-gray-700 dark:focus:border-indigo-500"
                            :value="old('twitter_url', $user->twitter_url)" placeholder="https://twitter.com/..." />
                        <x-input-error class="mt-2" :messages="$errors->get('twitter_url')" />
                    </div>
                    <div>
                        <x-input-label for="behance_url" :value="__('Behance')" />
                        <x-text-input id="behance_url" name="behance_url" type="url"
                            class="mt-1 block w-full bg-gray-50 border-gray-200 focus:bg-white focus:border-indigo-500 dark:bg-gray-900/50 dark:border-gray-700 dark:focus:border-indigo-500"
                            :value="old('behance_url', $user->behance_url)" placeholder="https://behance.net/..." />
                        <x-input-error class="mt-2" :messages="$errors->get('behance_url')" />
                    </div>
                    <div>
                        <x-input-label for="dribbble_url" :value="__('Dribbble')" />
                        <x-text-input id="dribbble_url" name="dribbble_url" type="url"
                            class="mt-1 block w-full bg-gray-50 border-gray-200 focus:bg-white focus:border-indigo-500 dark:bg-gray-900/50 dark:border-gray-700 dark:focus:border-indigo-500"
                            :value="old('dribbble_url', $user->dribbble_url)" placeholder="https://dribbble.com/..." />
                        <x-input-error class="mt-2" :messages="$errors->get('dribbble_url')" />
                    </div>
                </div>

                <hr class="border-gray-100 dark:border-gray-700">

                <!-- Visibility & Save -->
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-200">Profile Visibility</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Make your profile visible to other
                            users via your public link.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_profile_public" value="1" class="sr-only peer" {{ old('is_profile_public', $user->is_profile_public) ? 'checked' : '' }}>
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600">
                        </div>
                    </label>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm transition">
                        Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- Script to preview chosen image -->
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const fileNameSpan = document.getElementById('file-name');
            const preview = document.getElementById('photo-preview');
            const placeholder = document.getElementById('photo-placeholder');

            if (file) {
                fileNameSpan.textContent = file.name;
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                fileNameSpan.textContent = 'Dosya seçilmedi';
            }
        }
    </script>
</x-app-layout>
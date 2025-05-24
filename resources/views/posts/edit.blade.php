@extends('layouts.app')

@section('content')
    <div class="py-6" dir="{{ App::isLocale('ar') ? 'rtl' : 'ltr' }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="md:flex md:items-center md:justify-between mb-6">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-semibold text-gray-900">{{ __('message.Edit Post') }}</h2>
                </div>
                <div class="mt-4 flex md:mt-0 md:{{ App::isLocale('ar') ? 'mr-4' : 'ml-4' }}">
                    <a href="{{ route('web.posts.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="{{ App::isLocale('ar') ? 'ml-2' : '-ml-1 mr-2' }} h-5 w-5 text-gray-500"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        {{ __('message.back_to_posts') }}
                    </a>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg">
                <form action="{{ route('web.posts.update', $post->id) }}" method="POST" class="space-y-6 p-6" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">
                            {{ __('message.title') }}
                        </label>
                        <input type="text" name="title" id="title"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 {{ App::isLocale('ar') ? 'text-right' : 'text-left' }}"
                            value="{{ old('title', $post->title) }}" required>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700">
                            {{ __('message.content') }}
                        </label>
                        <textarea name="content" id="content" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 {{ App::isLocale('ar') ? 'text-right' : 'text-left' }}"
                            required>{{ old('content', $post->content) }}</textarea>
                        <div class="mt-1">
                            <span class="text-sm text-gray-500" id="charCount">0 characters</span>
                        </div>
                        @error('content')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">
                            {{ __('message.image') }}
                        </label>
                        @if($post->image_url)
                            <div class="mt-2" id="currentImage">
                                <div class="relative">
                                    <img src="{{ asset($post->image_url) }}" class="h-32 w-auto rounded" alt="Current Image">
                                    <button type="button" class="absolute top-0 right-0 mt-3 mr-3 bg-gray-100 rounded-full p-1 hover:bg-gray-200"
                                        onclick="removeCurrentImage()">
                                        <svg class="h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" 
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-blue-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="image"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>{{ __('message.upload_image') }}</span>
                                        <input id="image" name="image_url" type="file" class="sr-only" accept="image/*">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            </div>
                        </div>
                        <div id="imagePreview" class="mt-2"></div>
                        @error('image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            {{ __('message.platforms') }}
                        </label>
                        <div class="mt-2 grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4">
                            @foreach($userPlatforms as $platform)
                                <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:border-blue-500 {{ App::isLocale('ar') ? 'space-x-reverse' : 'space-x-3' }}">
                                    <input type="checkbox" name="platform_ids[]" value="{{ $platform->id }}"
                                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                        {{ in_array($platform->id, old('platform_ids', $post->platforms->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    <span>{{ $platform->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('platforms')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            {{ __('message.status') }}
                        </label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            onchange="toggleScheduledTime()">
                            <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>{{ __('message.draft') }}</option>
                            <option value="scheduled" {{ old('status', $post->status) === 'scheduled' ? 'selected' : '' }}>{{ __('message.scheduled') }}</option>
                            <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>{{ __('message.published') }}</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>                 
                    <div id="scheduledTimeContainer" class="{{ $post->status === 'scheduled' ? '' : 'hidden' }}">
                        <label for="scheduled_time" class="block text-sm font-medium text-gray-700">
                            {{ __('message.scheduled_time') }}
                        </label>
                        <input type="datetime-local" name="scheduled_time" id="scheduled_time"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            min="{{ now()->format('Y-m-d\\TH:i') }}"
                            {{ $post->status === 'scheduled' ? 'required' : '' }}
                            value="{{ old('scheduled_time', $post->scheduled_time ? date('Y-m-d\\TH:i', strtotime($post->scheduled_time)) : '') }}">
                        @error('scheduled_time')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3 {{ App::isLocale('ar') ? 'space-x-reverse' : '' }}">
                        <a href="{{ route('web.posts.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            {{ __('message.cancel') }}
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('message.Update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const contentTextarea = document.getElementById('content');
        const charCountSpan = document.getElementById('charCount');

        function updateCharCount() {
            const count = contentTextarea.value.length;
            charCountSpan.textContent = count === 1 ? '1 character' : `${count} characters`;
        }

        contentTextarea.addEventListener('input', updateCharCount);
        updateCharCount();

        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {

                    const currentImage = document.getElementById('currentImage');
                    if (currentImage) {
                        currentImage.style.display = 'none';
                    }

                    const previewContainer = document.getElementById('imagePreview');
                    previewContainer.innerHTML = `
                        <div class="relative mt-2">
                            <img src="${e.target.result}" class="h-32 w-auto rounded" alt="Preview">
                            <button type="button" onclick="removeImage()" class="absolute top-0 right-0 mt-1 mr-1 bg-gray-100 rounded-full p-1 hover:bg-gray-200">
                                <svg class="h-4 w-4 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" 
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    `;
                }
                reader.readAsDataURL(file);
            }
        });

        function removeImage() {
            document.getElementById('image').value = '';
            document.getElementById('imagePreview').innerHTML = '';
            const currentImage = document.getElementById('currentImage');
            if (currentImage) {
                currentImage.style.display = 'block';
            }
        }

        function removeCurrentImage() {
            const currentImage = document.getElementById('currentImage');
            if (currentImage) {
                currentImage.style.display = 'none';
            }
            const form = document.querySelector('form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'remove_image';
            input.value = '1';
            form.appendChild(input);
        }

        function toggleScheduledTime() {
            const status = document.getElementById('status').value;
            const scheduledTimeContainer = document.getElementById('scheduledTimeContainer');
            const scheduledTimeInput = document.getElementById('scheduled_time');

            if (status === 'scheduled') {
                scheduledTimeContainer.classList.remove('hidden');
                scheduledTimeInput.setAttribute('required', 'required');
                if (!scheduledTimeInput.value) {
                    scheduledTimeInput.value = '{{ now()->format('Y-m-d\TH:i') }}';
                }
            } else {
                scheduledTimeContainer.classList.add('hidden');
                scheduledTimeInput.removeAttribute('required');
                scheduledTimeInput.value = '';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleScheduledTime();
        });
    </script>
    @endpush
@endsection
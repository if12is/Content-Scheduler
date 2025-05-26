@extends('layouts.app')

@section('header')
Edit Post
@endsection

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Title Field -->
                <div class="sm:col-span-6">
                    <label for="title" class="block text-sm/6 font-medium text-gray-900">Title</label>
                    <div class="mt-2">
                        <input type="text" name="title" id="title" required autocomplete="off"
                            class="border border-gray-300 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-black placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-black sm:text-sm/6"
                            value="{{ old('title', $post->title) }}">
                    </div>
                    @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content Field -->
                <div class="sm:col-span-6">
                    <label for="content" class="block text-sm/6 font-medium text-gray-900">Content</label>
                    <div class="mt-2 relative">
                        <textarea id="content" name="content" rows="5"
                            class="border border-gray-300 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-black placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-black sm:text-sm/6 transition-colors duration-150"
                            maxlength="280">{{ old('content', $post->content) }}</textarea>
                        <div class="absolute bottom-2 right-2 text-xs text-gray-500" id="charCount">0/280</div>
                    </div>
                    @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Upload -->
                <div class="sm:col-span-6">
                    <label for="image_url" class="block text-sm/6 font-medium text-gray-900">Image</label>
                    <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                        <div class="text-center">
                            <div id="imagePreviewContainer" style="{{ $post->image_url ? '' : 'display: none;' }}">
                                <img id="imagePreview" class="mx-auto h-32 w-32 object-cover mb-4 rounded-md"
                                    src="{{ $post->image_url ? asset($post->image_url) : '' }}"
                                    alt="{{ $post->title }}">
                            </div>
                            <svg id="defaultIcon" class="mx-auto size-12 text-gray-300" viewBox="0 0 24 24"
                                fill="currentColor" aria-hidden="true" data-slot="icon"
                                style="{{ $post->image_url ? 'display: none;' : '' }}">
                                <path fill-rule="evenodd"
                                    d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <div class="mt-4 flex text-sm/6 text-gray-600 justify-center">
                                <label for="image_url"
                                    class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 focus-within:outline-hidden hover:text-indigo-500">
                                    <span>Upload a file</span>
                                    <input id="image_url" name="image_url" type="file" class="sr-only" accept="image/*"
                                        onchange="previewImage(this)">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs/5 text-gray-600">PNG, JPG, GIF up to 10MB</p>
                            @if($post->image_url)
                            <p class="mt-2 text-xs text-gray-500">Leave empty to keep current image</p>
                            @endif
                        </div>
                    </div>
                    @error('image_url')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Platform Selector -->
                <div class="sm:col-span-3">
                    <label for="platform" class="block text-sm/6 font-medium text-gray-900">Platform</label>
                    <div class="mt-2">
                        <select id="platform" name="platform" required
                            class="border border-gray-300 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-black placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-black sm:text-sm/6">
                            <option value="" disabled>Select Platform</option>
                            @foreach($platforms as $platform)
                            <option value="{{ $platform->name }}" {{ old('platform', $post->platforms->first()->name ??
                                '') == $platform->name ? 'selected' : '' }}>
                                {{ ucfirst($platform->name) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @error('platform')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date/Time Picker -->
                <div class="sm:col-span-3">
                    <label for="scheduled_time" class="block text-sm/6 font-medium text-gray-900">Scheduled
                        Date/Time</label>
                    <div class="mt-2 flex space-x-2">
                        <div class="flex-grow">
                            <input type="date" name="scheduled_date" id="scheduled_date" required
                                class="border border-gray-300 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-black placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-black sm:text-sm/6"
                                value="{{ old('scheduled_date', $post->scheduled_time ? $post->scheduled_time->format('Y-m-d') : '') }}">
                        </div>
                        <div class="flex-grow">
                            <input type="time" name="scheduled_time" id="scheduled_time" required
                                class="border border-gray-300 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-black placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-black sm:text-sm/6"
                                value="{{ old('scheduled_time', $post->scheduled_time ? $post->scheduled_time->format('H:i') : '') }}">
                        </div>
                    </div>
                    @error('scheduled_time')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 flex justify-end space-x-3">
            <a href="{{ route('posts.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                Cancel
            </a>
            <button type="submit" name="status" value="draft"
                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-150">
                Save as Draft
            </button>
            <button type="submit" name="status" value="scheduled"
                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                Schedule Post
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Character counter for content
        const contentTextarea = document.getElementById('content');
        const charCount = document.getElementById('charCount');

        // Initial character count
        const initialCount = contentTextarea.value.length;
        charCount.textContent = `${initialCount}/280`;

        // Apply appropriate styling based on count
        if (initialCount > 240 && initialCount <= 270) {
            charCount.classList.add('text-yellow-500');
        } else if (initialCount > 270) {
            charCount.classList.add('text-red-500');
        }

        contentTextarea.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = `${count}/280`;

            // Visual indicator when approaching limit
            charCount.classList.remove('text-yellow-500', 'text-red-500');

            if (count > 240 && count <= 270) {
                charCount.classList.add('text-yellow-500');
            } else if (count > 270) {
                charCount.classList.add('text-red-500');
            }
        });

        // Animation for form fields
        const formFields = document.querySelectorAll('input, textarea, select');
        formFields.forEach((field, index) => {
            field.style.opacity = '0';
            field.style.transform = 'translateY(10px)';
            field.style.transition = 'opacity 0.3s ease, transform 0.3s ease';

            setTimeout(() => {
                field.style.opacity = '1';
                field.style.transform = 'translateY(0)';
            }, 100 + (index * 50));
        });
    });

    // Image preview function
    function previewImage(input) {
        const imagePreview = document.getElementById('imagePreview');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        const defaultIcon = document.getElementById('defaultIcon');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreviewContainer.style.display = 'block';
                if (defaultIcon) defaultIcon.style.display = 'none';
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            imagePreviewContainer.style.display = 'none';
            if (defaultIcon) defaultIcon.style.display = 'block';
        }
    }
</script>
@endsection
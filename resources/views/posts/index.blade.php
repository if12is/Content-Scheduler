@extends('layouts.app')

@section('header')
Posts
@endsection

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
    <div class="border-b border-gray-200 px-4 py-5 sm:px-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">All Posts</h3>
            <p class="mt-1 text-sm text-gray-500">
                Manage your scheduled and draft posts
            </p>
        </div>
        <div>
            <a href="{{ route('posts.create') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150 ease-in-out">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                New Post
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
        <form action="{{ route('posts.index') }}" method="GET"
            class="flex flex-wrap items-center justify-between sm:flex-nowrap">
            <div class="flex-grow flex space-x-4">
                <div class="relative">
                    <select name="platform" onchange="this.form.submit()"
                        class="block appearance-none bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 transition-colors duration-150">
                        <option value="">All Platforms</option>
                        @foreach($platforms as $platform)
                        <option value="{{ $platform->name }}" {{ request('platform')==$platform->name ? 'selected' : ''
                            }}>
                            {{ ucfirst($platform->name) }}
                        </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                        </svg>
                    </div>
                </div>
                <div class="relative">
                    <select name="status" onchange="this.form.submit()"
                        class="block appearance-none bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 transition-colors duration-150">
                        <option value="">All Status</option>
                        <option value="scheduled" {{ request('status')=='scheduled' ? 'selected' : '' }}>Scheduled
                        </option>
                        <option value="published" {{ request('status')=='published' ? 'selected' : '' }}>Published
                        </option>
                        <option value="draft" {{ request('status')=='draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="mt-4 sm:mt-0">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts..."
                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md transition-colors duration-150">
                    <button type="submit" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Posts List -->
    <div class="bg-white">
        <ul role="list" class="divide-y divide-gray-200">
            @forelse($posts as $post)
            <li
                class="relative bg-white py-5 px-4 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500 transition-colors duration-150">
                <div class="flex justify-between">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center">
                            <div class="h-10 w-10 flex-shrink-0">
                                @if($post->image_url)
                                <img class="h-10 w-10 rounded-md object-cover" src="{{ asset($post->image_url) }}"
                                    alt="{{ $post->title }}">
                                @else
                                <div
                                    class="h-10 w-10 flex-shrink-0 bg-gray-200 rounded-md flex items-center justify-center">
                                    <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <div class="flex items-center">
                                    <span class="truncate text-sm font-medium text-indigo-600">
                                        @if($post->platforms->isNotEmpty())
                                        {{ ucfirst($post->platforms->first()->name) }}
                                        @else
                                        No Platform
                                        @endif
                                    </span>
                                    <span class="ml-1.5 inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                            @if($post->status == 'scheduled') bg-green-100 text-green-800 @endif
                                            @if($post->status == 'draft') bg-yellow-100 text-yellow-800 @endif
                                            @if($post->status == 'published') bg-blue-100 text-blue-800 @endif
                                            @if($post->status == 'failed') bg-red-100 text-red-800 @endif
                                        ">
                                        {{ ucfirst($post->status) }}
                                    </span>
                                </div>
                                <a href="{{ route('posts.edit', $post->id) }}" class="block">
                                    <p class="truncate text-sm font-medium text-gray-900">{{ $post->title }}</p>
                                    <p class="truncate text-sm text-gray-500">{{ Str::limit($post->content, 50) }}</p>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col items-end ml-2">
                        @if($post->scheduled_time)
                        <p class="text-sm text-gray-900">{{ is_null($post->scheduled_time) ? 'Not Scheduled' :
                            $post->scheduled_time->format('M d, Y') }}</p>
                        <p class="text-xs text-gray-500">{{ is_null($post->scheduled_time) ? 'Not Scheduled' :
                            $post->scheduled_time->format('h:i A') }}</p>
                        @endif
                        <div class="mt-2 flex space-x-2">
                            <a href="{{ route('posts.edit', $post->id) }}"
                                class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="text-red-600 hover:text-red-900 delete-post"
                                    data-id="{{ $post->id }}">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </li>
            @empty
            <li class="py-8 text-center text-gray-500">
                <p>No posts found.</p>
                <p class="mt-2">
                    <a href="{{ route('posts.create') }}" class="text-indigo-600 hover:text-indigo-900">
                        Create your first post
                    </a>
                </p>
            </li>
            @endforelse
        </ul>
    </div>

    <!-- Pagination -->
    @if($posts->isNotEmpty())
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        {{ $posts->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete post button clicks
        const deleteButtons = document.querySelectorAll('.delete-post');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const postId = this.dataset.id;
                const form = this.closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Animation for list items
        const listItems = document.querySelectorAll('li');
        listItems.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateX(-10px)';
            item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';

            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            }, 100 + (index * 50));
        });
    });
</script>
@endsection
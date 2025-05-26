@extends('layouts.app')

@section('header')
Dashboard
@endsection

@section('content')
<div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
    <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Content Calendar</h3>
                <span
                    class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    {{ $calendarData['monthName'] }} {{ $calendarData['year'] }}
                </span>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('dashboard', ['month' => $calendarData['month'] === 1 ? 12 : $calendarData['month'] - 1, 'year' => $calendarData['month'] === 1 ? $calendarData['year'] - 1 : $calendarData['year']]) }}"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150 ease-in-out">
                    Previous Month
                </a>
                <a href="{{ route('dashboard', ['month' => $calendarData['month'] === 12 ? 1 : $calendarData['month'] + 1, 'year' => $calendarData['month'] === 12 ? $calendarData['year'] + 1 : $calendarData['year']]) }}"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150 ease-in-out">
                    Next Month
                </a>
            </div>
        </div>
    </div>
    <div class="overflow-hidden">
        <!-- Calendar View -->
        <div class="grid grid-cols-7 gap-px bg-gray-200">
            <div class="bg-gray-50 p-2 text-center">Sun</div>
            <div class="bg-gray-50 p-2 text-center">Mon</div>
            <div class="bg-gray-50 p-2 text-center">Tue</div>
            <div class="bg-gray-50 p-2 text-center">Wed</div>
            <div class="bg-gray-50 p-2 text-center">Thu</div>
            <div class="bg-gray-50 p-2 text-center">Fri</div>
            <div class="bg-gray-50 p-2 text-center">Sat</div>

            <!-- Empty cells for days before the first day of the month -->
            @for ($i = 0; $i < $calendarData['firstDayOffset']; $i++) <div class="bg-gray-50 p-2 h-24">
        </div>
        @endfor

        <!-- Calendar days with posts -->
        @for ($day = 1; $day <= $calendarData['daysInMonth']; $day++) <div
            class="bg-white p-2 h-24 hover:bg-gray-50 transition-colors duration-150">
            <div class="font-semibold">{{ $day }}</div>

            <!-- Display posts for this day -->
            @if(isset($calendarData['posts'][$day]))
            <div class="mt-1">
                @foreach($calendarData['posts'][$day] as $post)
                <div class="@switch($post->platforms->first()->name ?? 'default')
                                    @case('twitter') bg-blue-100 hover:bg-blue-200 @break
                                    @case('instagram') bg-pink-100 hover:bg-pink-200 @break
                                    @case('facebook') bg-indigo-100 hover:bg-indigo-200 @break
                                    @case('linkedin') bg-blue-100 hover:bg-blue-200 @break
                                    @default bg-gray-100 hover:bg-gray-200
                                @endswitch p-1 rounded text-xs mb-1 cursor-pointer transition-colors duration-150">
                    <a href="{{ route('posts.edit', $post->id) }}" class="block">
                        {{ ucfirst($post->platforms->first()->name ?? 'Unknown') }} - {{
                        is_null($post->scheduled_time) ? 'Not Scheduled' : $post->scheduled_time->format('g:i A') }}
                    </a>
                </div>
                @endforeach
            </div>
            @endif
    </div>
    @endfor
</div>


<!-- List View with Filters -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Upcoming Posts</h3>
            <div class="flex space-x-3">
                <form action="{{ route('dashboard') }}" method="GET" class="flex space-x-3">
                    <div class="relative">
                        <select name="platform"
                            class="block appearance-none bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 transition-colors duration-150"
                            onchange="this.form.submit()">
                            <option value="">All Platforms</option>
                            @foreach($platforms as $platform)
                            <option value="{{ $platform->name }}" {{ request('platform')==$platform->name ? 'selected' :
                                '' }}>
                                {{ ucfirst($platform->name) }}
                            </option>
                            @endforeach
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                            </svg>
                        </div>
                    </div>
                    <div class="relative">
                        <select name="status"
                            class="block appearance-none bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500 transition-colors duration-150"
                            onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="scheduled" {{ request('status')=='scheduled' ? 'selected' : '' }}>Scheduled
                            </option>
                            <option value="published" {{ request('status')=='published' ? 'selected' : '' }}>Published
                            </option>
                            <option value="failed" {{ request('status')=='failed' ? 'selected' : '' }}>Failed</option>
                            <option value="draft" {{ request('status')=='draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                            </svg>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Content
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Platform
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Schedule
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($posts as $post)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($post->image_path)
                            <div class="h-10 w-10 flex-shrink-0">
                                <img class="h-10 w-10 rounded-md object-cover"
                                    src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}">
                            </div>
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
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $post->title }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ Str::limit($post->content, 50) }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            {{-- {{ $post->platforms->first() }} --}}
                            {{ ucfirst($post->platforms->first()->name ?? 'Unknown') }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ is_null($post->scheduled_time) ? 'Not Scheduled' :
                            $post->scheduled_time->format('g:i A') }}</div>
                        <div class="text-sm text-gray-500">{{ is_null($post->scheduled_time) ? 'Not Scheduled' :
                            $post->scheduled_time->format('g:i A') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($post->status == 'scheduled') bg-green-100 text-green-800 @endif
                            @if($post->status == 'draft') bg-yellow-100 text-yellow-800 @endif
                            @if($post->status == 'published') bg-blue-100 text-blue-800 @endif
                            @if($post->status == 'failed') bg-red-100 text-red-800 @endif">
                            {{ ucfirst($post->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('posts.edit', $post->id) }}"
                            class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900"
                                onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        No upcoming posts found. <a href="{{ route('posts.create') }}"
                            class="text-indigo-600 hover:text-indigo-900">Create a post</a> to get started.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        {{ $posts->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Example of animation effect
        const calendarDays = document.querySelectorAll('.bg-white.p-2.h-24');
        calendarDays.forEach((day, index) => {
            setTimeout(() => {
                day.classList.add('animate-fade-in');
            }, index * 20);
        });
    });
</script>
<style>
    /* Simple fade-in animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }
</style>
@endsection

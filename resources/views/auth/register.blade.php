@extends('layouts.auth')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl">
            <div class="px-6 py-4 bg-indigo-600">
                <h1 class="text-xl font-bold text-white text-center">{{ __('Register') }}</h1>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Name') }}
                        </label>
                        <input id="name" type="text"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-300 @error('name') border-red-500 @enderror"
                            name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                        @error('name')
                        <span class="text-sm text-red-600 mt-1 block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Email Address') }}
                        </label>
                        <input id="email" type="email"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-300 @error('email') border-red-500 @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email">

                        @error('email')
                        <span class="text-sm text-red-600 mt-1 block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Password') }}
                        </label>
                        <input id="password" type="password"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-300 @error('password') border-red-500 @enderror"
                            name="password" required autocomplete="new-password">

                        @error('password')
                        <span class="text-sm text-red-600 mt-1 block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Confirm Password') }}
                        </label>
                        <input id="password-confirm" type="password"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-300"
                            name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md transition-colors duration-200">
                            {{ __('Register') }}
                        </button>

                        <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                            {{ __('Already have an account?') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.auth')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl">
            <div class="px-6 py-4 bg-indigo-600">
                <h1 class="text-xl font-bold text-white text-center">{{ __('Login') }}</h1>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            {{ __('Email Address') }}
                        </label>
                        <input id="email" type="email"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-300 @error('email') border-red-500 @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

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
                            name="password" required autocomplete="current-password">

                        @error('password')
                        <span class="text-sm text-red-600 mt-1 block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="flex items-center">
                            <input
                                class="rounded border-gray-300 text-indigo-600 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="ml-2 text-sm text-gray-600" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mb-4">
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md transition-colors duration-200">
                            {{ __('Login') }}
                        </button>

                        @if (Route::has('password.request'))
                        <a class="text-sm text-indigo-600 hover:text-indigo-800" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                        @endif
                    </div>

                    @if (Route::has('register'))
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            {{ __('Don\'t have an account?') }}
                            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                {{ __('Register') }}
                            </a>
                        </p>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
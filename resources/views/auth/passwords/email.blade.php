@extends('layouts.auth')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl">
            <div class="px-6 py-4 bg-indigo-600">
                <h1 class="text-xl font-bold text-white text-center">{{ __('Reset Password') }}</h1>
            </div>

            <div class="p-6">
                @if (session('status'))
                <div class="mb-4 px-4 py-3 bg-green-100 border border-green-400 text-green-700 rounded" role="alert">
                    {{ session('status') }}
                </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
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

                    <div class="flex items-center justify-between">
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md transition-colors duration-200">
                            {{ __('Send Password Reset Link') }}
                        </button>

                        <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                            {{ __('Back to login') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
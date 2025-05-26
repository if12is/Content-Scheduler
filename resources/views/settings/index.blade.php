@extends('layouts.app')

@section('header')
Settings
@endsection

@section('content')
<div class="grid grid-cols-1 gap-6 md:grid-cols-12">
    <!-- Sidebar Navigation -->
    <div class="md:col-span-3">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="border-b border-gray-200 bg-gray-50 px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Settings Menu</h3>
            </div>
            <div class="border-t border-gray-200">
                <div class="p-0">
                    <nav class="flex flex-col">
                        <a href="#platforms"
                            class="border-l-4 border-indigo-500 bg-indigo-50 py-3 px-6 text-indigo-700 font-medium text-sm setting-link"
                            data-target="platforms">
                            Platform Management
                        </a>
                        {{-- <a href="#account"
                            class="border-l-4 border-transparent py-3 px-6 hover:bg-gray-50 transition-colors duration-150 text-gray-700 font-medium text-sm setting-link"
                            data-target="account">
                            Account Settings
                        </a> --}}
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="md:col-span-9">
        <!-- Platform Management Section -->
        <div id="platforms" class="settings-section bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Platform Management</h3>
                <p class="mt-1 text-sm text-gray-500">Connect your social media accounts to schedule posts.</p>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="space-y-6">
                    @foreach($platforms as $platform)
                    @php
                    $userPlatform = $userPlatforms->firstWhere('platform_id', $platform->id);
                    $credentials = $userPlatform ? $userPlatform->credentials : [];
                    if (!is_array($credentials)) $credentials = json_decode($credentials, true) ?: [];
                    @endphp
                    <div
                        class="platform-card bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-150 overflow-hidden">
                        <div class="p-4 sm:px-6 flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    @if(strtolower($platform->name) === 'twitter')
                                    <svg class="h-10 w-10 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                    </svg>
                                    @elseif(strtolower($platform->name) === 'instagram')
                                    <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none">
                                        <path
                                            d="M12 2C14.717 2 15.056 2.01 16.122 2.06C17.187 2.11 17.912 2.277 18.55 2.525C19.21 2.779 19.766 3.123 20.322 3.678C20.8305 4.1779 21.224 4.78259 21.475 5.45C21.722 6.087 21.89 6.813 21.94 7.878C21.987 8.944 22 9.283 22 12C22 14.717 21.99 15.056 21.94 16.122C21.89 17.187 21.722 17.912 21.475 18.55C21.2247 19.2178 20.8311 19.8226 20.322 20.322C19.822 20.8303 19.2173 21.2238 18.55 21.475C17.913 21.722 17.187 21.89 16.122 21.94C15.056 21.987 14.717 22 12 22C9.283 22 8.944 21.99 7.878 21.94C6.813 21.89 6.088 21.722 5.45 21.475C4.78233 21.2245 4.17753 20.8309 3.678 20.322C3.16941 19.8222 2.77593 19.2175 2.525 18.55C2.277 17.913 2.11 17.187 2.06 16.122C2.013 15.056 2 14.717 2 12C2 9.283 2.01 8.944 2.06 7.878C2.11 6.812 2.277 6.088 2.525 5.45C2.77524 4.78218 3.1688 4.17732 3.678 3.678C4.17767 3.16923 4.78243 2.77573 5.45 2.525C6.088 2.277 6.812 2.11 7.878 2.06C8.944 2.013 9.283 2 12 2Z"
                                            fill="url(#paint0_radial)" />
                                        <path
                                            d="M12 2C14.717 2 15.056 2.01 16.122 2.06C17.187 2.11 17.912 2.277 18.55 2.525C19.21 2.779 19.766 3.123 20.322 3.678C20.8305 4.1779 21.224 4.78259 21.475 5.45C21.722 6.087 21.89 6.813 21.94 7.878C21.987 8.944 22 9.283 22 12C22 14.717 21.99 15.056 21.94 16.122C21.89 17.187 21.722 17.912 21.475 18.55C21.2247 19.2178 20.8311 19.8226 20.322 20.322C19.822 20.8303 19.2173 21.2238 18.55 21.475C17.913 21.722 17.187 21.89 16.122 21.94C15.056 21.987 14.717 22 12 22C9.283 22 8.944 21.99 7.878 21.94C6.813 21.89 6.088 21.722 5.45 21.475C4.78233 21.2245 4.17753 20.8309 3.678 20.322C3.16941 19.8222 2.77593 19.2175 2.525 18.55C2.277 17.913 2.11 17.187 2.06 16.122C2.013 15.056 2 14.717 2 12C2 9.283 2.01 8.944 2.06 7.878C2.11 6.812 2.277 6.088 2.525 5.45C2.77524 4.78218 3.1688 4.17732 3.678 3.678C4.17767 3.16923 4.78243 2.77573 5.45 2.525C6.088 2.277 6.812 2.11 7.878 2.06C8.944 2.013 9.283 2 12 2Z"
                                            fill="url(#paint1_radial)" />
                                        <path
                                            d="M12 15.5C10.067 15.5 8.5 13.933 8.5 12C8.5 10.067 10.067 8.5 12 8.5C13.933 8.5 15.5 10.067 15.5 12C15.5 13.933 13.933 15.5 12 15.5Z"
                                            fill="white" />
                                        <path
                                            d="M16 9.5C16.8284 9.5 17.5 8.82843 17.5 8C17.5 7.17157 16.8284 6.5 16 6.5C15.1716 6.5 14.5 7.17157 14.5 8C14.5 8.82843 15.1716 9.5 16 9.5Z"
                                            fill="white" />
                                    </svg>
                                    @elseif(strtolower($platform->name) === 'facebook')
                                    <svg class="h-10 w-10 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                    @elseif(strtolower($platform->name) === 'linkedin')
                                    <svg class="h-10 w-10 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                    </svg>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">{{ ucfirst($platform->name) }}</h4>
                                    <p class="text-sm text-gray-500">{{ $platform->type }}</p>
                                </div>
                            </div>
                            <div>
                                @if($userPlatform)
                                <form action="{{ route('settings.platform.disconnect', $platform->name) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="delete-platform inline-flex items-center px-3 py-1.5 border border-red-300 shadow-sm text-sm font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 ml-2">Disconnect</button>
                                </form>
                                @endif
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 platform-settings">
                            <form class="space-y-4" action="{{ route('settings.platform.update', $platform->name) }}"
                                method="POST">
                                @csrf
                                @php
                                $fields = [];
                                switch(strtolower($platform->name)) {
                                case 'twitter':
                                $fields = [
                                ['api_key', 'API Key', false],
                                ['api_secret_key', 'API Secret Key', true],
                                ['access_token', 'Access Token', false],
                                ['access_token_secret', 'Access Token Secret', true],
                                ]; break;
                                case 'linkedin':
                                $fields = [
                                ['client_id', 'Client ID', false],
                                ['client_secret', 'Client Secret', true],
                                ['access_token', 'Access Token', true],
                                ['organization_id', 'Organization ID', false],
                                ]; break;
                                case 'facebook':
                                $fields = [
                                ['app_id', 'App ID', false],
                                ['app_secret', 'App Secret', true],
                                ['page_id', 'Page ID', false],
                                ['user_access_token', 'User Access Token', true],
                                ]; break;
                                case 'instagram':
                                $fields = [
                                ['app_id', 'App ID', false],
                                ['app_secret', 'App Secret', true],
                                ['instagram_business_id', 'Instagram Business ID', false],
                                ['user_access_token', 'User Access Token', true],
                                ]; break;
                                }
                                @endphp
                                @foreach($fields as [$key, $label, $sensitive])
                                <div class="mt-2">
                                    <label for="{{ $platform->name }}_{{ $key }}"
                                        class="block text-sm/6 font-medium text-gray-900">{{ $label }}</label>
                                    <div class="mt-2">
                                        <div
                                            class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                            <input type="{{ $sensitive ? 'password' : 'text' }}" name="{{ $key }}"
                                                id="{{ $platform->name }}_{{ $key }}"
                                                class="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6"
                                                value="{{ app('App\\Services\\SettingsService')->getCredentialDisplay($credentials, $key, $sensitive) }}"
                                                placeholder="{{ $label }}">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                <div class="flex justify-end space-x-2">
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm text-sm font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Other sections (hidden by default) -->
        <div id="account" class="settings-section hidden bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="border-b border-gray-200 px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Account Settings</h3>
                <p class="mt-1 text-sm text-gray-500">Manage your account details and preferences.</p>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <!-- Account settings content -->
                <p>Account settings will be implemented here.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Settings navigation
        const settingLinks = document.querySelectorAll('.setting-link');
        const settingSections = document.querySelectorAll('.settings-section');

        settingLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const target = this.dataset.target;

                // Update active link styles
                settingLinks.forEach(l => {
                    l.classList.remove('border-indigo-500', 'bg-indigo-50', 'text-indigo-700');
                    l.classList.add('border-transparent', 'text-gray-700');
                });

                this.classList.remove('border-transparent', 'text-gray-700');
                this.classList.add('border-indigo-500', 'bg-indigo-50', 'text-indigo-700');

                // Show selected section, hide others with animation
                settingSections.forEach(section => {
                    if (section.id === target) {
                        section.classList.remove('hidden');
                        section.style.opacity = 0;
                        section.style.transform = 'translateY(10px)';

                        setTimeout(() => {
                            section.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                            section.style.opacity = 1;
                            section.style.transform = 'translateY(0)';
                        }, 50);
                    } else {
                        section.classList.add('hidden');
                    }
                });
            });
        });

        // Platform connect buttons
        const connectButtons = document.querySelectorAll('.platform-connect');

        connectButtons.forEach(button => {
            button.addEventListener('click', function() {
                const platform = this.dataset.platform;
                const settingsPanel = document.getElementById(`${platform}-settings`);

                if (settingsPanel.classList.contains('hidden')) {
                    // Show settings with slide down animation
                    settingsPanel.style.maxHeight = '0';
                    settingsPanel.style.overflow = 'hidden';
                    settingsPanel.classList.remove('hidden');

                    setTimeout(() => {
                        settingsPanel.style.transition = 'max-height 0.3s ease-in-out';
                        settingsPanel.style.maxHeight = '500px'; // Arbitrary large value
                    }, 10);

                    this.textContent = 'Hide Settings';
                } else {
                    // Hide settings with slide up animation
                    settingsPanel.style.maxHeight = '0';

                    setTimeout(() => {
                        settingsPanel.classList.add('hidden');
                    }, 300);

                    this.textContent = 'Connect';
                }
            });
        });

        // Delete confirmation example
        const deleteButtons = document.querySelectorAll('.delete-platform');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will disconnect your account from the platform.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, disconnect it!',
                    position: 'top-end',
                    toast: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Disconnected!',
                            text: 'The platform has been disconnected.',
                            showConfirmButton: false,
                            timer: 1500,
                            toast: true
                        });
                    }
                });
            });
        });
    });
</script>
@endsection
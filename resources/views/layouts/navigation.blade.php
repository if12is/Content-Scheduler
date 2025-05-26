<nav class="bg-indigo-600">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="ml-2 text-xl font-bold text-white">Content Scheduler</span>
                    </a>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="{{ route('dashboard') }}"
                            class="{{ request()->routeIs('dashboard') ? 'bg-indigo-700' : 'hover:bg-indigo-500 hover:bg-opacity-75' }} text-white rounded-md px-3 py-2 text-sm font-medium transition-colors duration-150 ease-in-out">
                            Dashboard
                        </a>
                        <a href="{{ route('posts.index') }}"
                            class="{{ request()->routeIs('posts.*') ? 'bg-indigo-700' : 'hover:bg-indigo-500 hover:bg-opacity-75' }} text-white rounded-md px-3 py-2 text-sm font-medium transition-colors duration-150 ease-in-out">
                            Posts
                        </a>
                        <a href="{{ route('settings.index') }}"
                            class="{{ request()->routeIs('settings.*') ? 'bg-indigo-700' : 'hover:bg-indigo-500 hover:bg-opacity-75' }} text-white rounded-md px-3 py-2 text-sm font-medium transition-colors duration-150 ease-in-out">
                            Settings
                        </a>
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    <div class="relative ml-3">
                        <div>
                            <button type="button"
                                class="flex max-w-xs items-center rounded-full bg-indigo-600 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-600"
                                id="user-menu-button">
                                <span class="sr-only">Open user menu</span>
                                <span
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-indigo-700">
                                    <span class="text-sm font-medium leading-none text-white">{{
                                        substr(auth()->user()->name ?? 'User', 0, 1) }}</span>
                                </span>
                            </button>
                        </div>
                        <!-- Dropdown menu -->
                        <div id="user-menu-dropdown"
                            class="hidden absolute right-0 z-50 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="-mr-2 flex md:hidden">
                <!-- Mobile menu button -->
                <button type="button"
                    class="inline-flex items-center justify-center rounded-md bg-indigo-600 p-2 text-indigo-200 hover:bg-indigo-500 hover:bg-opacity-75 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-600"
                    id="mobile-menu-button">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div class="hidden md:hidden" id="mobile-menu">
        <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
            <a href="{{ route('dashboard') }}"
                class="{{ request()->routeIs('dashboard') ? 'bg-indigo-700' : 'hover:bg-indigo-500 hover:bg-opacity-75' }} text-white block rounded-md px-3 py-2 text-base font-medium">Dashboard</a>
            <a href="{{ route('posts.index') }}"
                class="{{ request()->routeIs('posts.*') ? 'bg-indigo-700' : 'hover:bg-indigo-500 hover:bg-opacity-75' }} text-white block rounded-md px-3 py-2 text-base font-medium">Posts</a>
            <a href="{{ route('settings.index') }}"
                class="{{ request()->routeIs('settings.*') ? 'bg-indigo-700' : 'hover:bg-indigo-500 hover:bg-opacity-75' }} text-white block rounded-md px-3 py-2 text-base font-medium">Settings</a>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });

        // User menu dropdown
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenuDropdown = document.getElementById('user-menu-dropdown');

        userMenuButton.addEventListener('click', function(e) {
            e.stopPropagation();
            userMenuDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!userMenuDropdown.classList.contains('hidden')) {
                userMenuDropdown.classList.add('hidden');
            }
        });
        // Prevent closing when clicking inside dropdown
        userMenuDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
</script>
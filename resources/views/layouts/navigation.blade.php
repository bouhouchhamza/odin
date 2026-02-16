@php
    $latestNotifications = Auth::user()->notifications()->latest()->limit(10)->get();
    $unreadNotificationsCount = Auth::user()->unreadNotifications()->count();
@endphp

<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 sm:gap-3">
                <div class="relative" data-notification-root>
                    <button
                        type="button"
                        class="relative rounded-full p-2 text-gray-600 hover:bg-gray-100 hover:text-gray-900"
                        data-notification-toggle
                        aria-label="Notifications"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 2a4 4 0 00-4 4v2.586L5.293 10.293A1 1 0 006 12h8a1 1 0 00.707-1.707L14 8.586V6a4 4 0 00-4-4z" />
                            <path d="M8 13a2 2 0 104 0H8z" />
                        </svg>
                        <span
                            class="{{ $unreadNotificationsCount > 0 ? '' : 'hidden ' }}absolute -right-1 -top-1 inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-xs text-white"
                            data-notification-badge
                        >
                            {{ $unreadNotificationsCount }}
                        </span>
                    </button>

                    <div class="absolute right-0 z-50 mt-2 hidden w-96 rounded-md border border-gray-200 bg-white shadow-lg" data-notification-menu>
                        <div class="flex items-center justify-between border-b px-3 py-2">
                            <p class="text-sm font-semibold text-gray-800">Notifications</p>
                            <form method="POST" action="{{ route('notifications.read-all') }}" data-notification-read-all-form>
                                @csrf
                                @method('PATCH')
                                <button class="text-xs text-indigo-600 hover:underline">Mark all as read</button>
                            </form>
                        </div>

                        <ul class="max-h-80 overflow-y-auto" data-notification-list>
                            @forelse($latestNotifications as $notification)
                                @php
                                    $notificationData = $notification->data ?? [];
                                    $notificationTitle = $notificationData['link_title']
                                        ?? ucfirst(str_replace('_', ' ', (string) ($notificationData['type'] ?? class_basename($notification->type))));
                                    $notificationMessage = ucfirst(str_replace('_', ' ', (string) ($notificationData['type'] ?? 'notification')));
                                    $notificationUrl = $notificationData['url'] ?? route('notifications.index');
                                @endphp

                                <li
                                    class="border-b border-gray-100 {{ $notification->read_at ? '' : 'bg-blue-50' }}"
                                    data-notification-item
                                >
                                    <a href="{{ $notificationUrl }}" class="block px-3 py-2 hover:bg-gray-50">
                                        <p class="text-sm font-medium text-gray-900">{{ $notificationTitle }}</p>
                                        <p class="text-xs text-gray-600">{{ $notificationMessage }}</p>
                                        <p class="mt-1 text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                                    </a>

                                    @if(!$notification->read_at)
                                        <div class="px-3 pb-2">
                                            <form method="POST" action="{{ route('notifications.read', $notification) }}" data-notification-read-form>
                                                @csrf
                                                @method('PATCH')
                                                <button class="text-xs text-indigo-600 hover:underline">Mark as read</button>
                                            </form>
                                        </div>
                                    @endif
                                </li>
                            @empty
                                <li class="px-3 py-4 text-sm text-gray-500" data-notification-empty>No notifications yet.</li>
                            @endforelse
                        </ul>

                        <div class="border-t px-3 py-2">
                            <a href="{{ route('notifications.index') }}" class="text-sm text-indigo-600 hover:underline">
                                View all notifications
                            </a>
                        </div>
                    </div>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('notifications.index')">
                    {{ __('Notifications') }}
                    @if($unreadNotificationsCount > 0)
                        <span class="ml-1 inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-xs text-white">
                            {{ $unreadNotificationsCount }}
                        </span>
                    @endif
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>

    @once
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                const sendForm = async function (form) {
                    const payload = new URLSearchParams(new FormData(form));

                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                        },
                        body: payload.toString()
                    });

                    if (!response.ok) {
                        throw new Error('Request failed');
                    }
                };

                document.querySelectorAll('[data-notification-root]').forEach(function (root) {
                    const toggle = root.querySelector('[data-notification-toggle]');
                    const menu = root.querySelector('[data-notification-menu]');
                    const badge = root.querySelector('[data-notification-badge]');

                    if (!toggle || !menu) {
                        return;
                    }

                    const getCount = function () {
                        if (!badge || badge.classList.contains('hidden')) {
                            return 0;
                        }
                        return Number.parseInt(badge.textContent.trim(), 10) || 0;
                    };

                    const setCount = function (count) {
                        if (!badge) {
                            return;
                        }

                        if (count <= 0) {
                            badge.textContent = '0';
                            badge.classList.add('hidden');
                            return;
                        }

                        badge.textContent = String(count);
                        badge.classList.remove('hidden');
                    };

                    toggle.addEventListener('click', function (event) {
                        event.stopPropagation();
                        menu.classList.toggle('hidden');
                    });

                    document.addEventListener('click', function (event) {
                        if (!root.contains(event.target)) {
                            menu.classList.add('hidden');
                        }
                    });

                    root.querySelectorAll('[data-notification-read-form]').forEach(function (form) {
                        form.addEventListener('submit', async function (event) {
                            event.preventDefault();

                            try {
                                await sendForm(form);
                                form.remove();

                                const item = form.closest('[data-notification-item]');
                                if (item) {
                                    item.classList.remove('bg-blue-50');
                                }

                                setCount(Math.max(0, getCount() - 1));
                            } catch (error) {
                                console.error(error);
                            }
                        });
                    });

                    const readAllForm = root.querySelector('[data-notification-read-all-form]');
                    if (readAllForm) {
                        readAllForm.addEventListener('submit', async function (event) {
                            event.preventDefault();

                            try {
                                await sendForm(readAllForm);
                                root.querySelectorAll('[data-notification-item]').forEach(function (item) {
                                    item.classList.remove('bg-blue-50');
                                });
                                root.querySelectorAll('[data-notification-read-form]').forEach(function (form) {
                                    form.remove();
                                });
                                setCount(0);
                            } catch (error) {
                                console.error(error);
                            }
                        });
                    }
                });
            });
        </script>
    @endonce
</nav>

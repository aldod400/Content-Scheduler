<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('message.Content Scheduler') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['var(--font-sans)'],
                    },
                },
            },
        }
    </script>
    <style>
        .alert-message {
            animation: slideIn 0.3s ease-out, fadeOut 0.5s ease-in 4s forwards;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
            }
        }

        .dropdown-content {
            display: none;
            position: absolute;
            {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}
            : 0;
            min-width: 160px;
            z-index: 1;
            animation: fadeIn 0.2s ease-out;
        }

        .dropdown-content.show {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body class="font-sans antialiased {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="min-h-screen bg-gray-50">
        <!-- Messages -->
        <div class="fixed top-4 right-4 z-50 w-80 space-y-2">
            @if(session('success'))
                <div class="alert-message bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-lg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold">{{ __('message.Success') }}</p>
                            <p>{{ session('success') }}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()"
                            class="text-green-700 hover:text-green-900">
                            &times;
                        </button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert-message bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-lg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold">{{ __('message.Error') }}</p>
                            <p>{{ session('error') }}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-red-700 hover:text-red-900">
                            &times;
                        </button>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-message bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-lg">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold">{{ __('message.Validation Errors') }}</p>
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-red-700 hover:text-red-900">
                            &times;
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Navbar -->
        <nav class="bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('web.dashboard') }}" class="flex items-center">
                            <span class="text-xl font-bold text-indigo-600">{{ __('message.Content Scheduler') }}</span>
                        </a>
                        @auth
                            <div
                                class="hidden space-x-8 sm:ml-10 sm:flex {{ app()->getLocale() == 'ar' ? 'mr-10' : 'ml-10' }}">
                                <a href="{{ route('web.posts.index') }}"
                                    class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('web.posts.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }} {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}">
                                    {{ __('message.Posts') }}
                                </a>
                                <a href="{{ route('web.posts.create') }}"
                                    class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('web.posts.create') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }} {{ app()->getLocale() == 'ar' ? 'ml-2' : 'mr-2' }}">
                                    {{ __('message.Add Post') }}
                                </a> <a href="{{ route('web.platforms.index') }}"
                                    class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('web.platforms.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }}">
                                    {{ __('message.Platforms') }}
                                </a>
                                <a href="{{ route('web.logs.index') }}"
                                    class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('web.logs.*') ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }} flex items-center">
                                    <svg class="w-5 h-5 {{ App::isLocale('ar') ? 'ml-1' : 'mr-1' }}" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ __('message.Logs') }}
                                </a>
                            </div>
                        @endauth
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button id="languageDropdownButton"
                                class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-500 hover:text-gray-900 hover:bg-gray-50 focus:outline-none">
                                <span>{{ app()->getLocale() == 'ar' ? 'العربية' : 'English' }}</span>
                                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div id="languageDropdown"
                                class="dropdown-content mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1">
                                    <a href="{{ route('language.switch', ['locale' => 'ar']) }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() == 'ar' ? 'bg-gray-100' : '' }}">
                                        {{ __('message.Arabic') }}
                                    </a>
                                    <a href="{{ route('language.switch', ['locale' => 'en']) }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ app()->getLocale() == 'en' ? 'bg-gray-100' : '' }}">
                                        {{ __('message.English') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        @auth
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('web.profile.get') }}"
                                    class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-700 hover:bg-gray-100 transition{{ request()->routeIs('web.profile.*') ? ' text-indigo-700' : '' }}">
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ auth()->user()->name }}</span>
                                </a>
                                <form method="POST" action="{{ route('web.logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-2 rounded-md text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50">
                                        {{ __('message.Logout') }}
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('web.view.login') }}"
                                    class="px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-gray-700 hover:bg-gray-50 {{ request()->routeIs('web.view.login') ? 'text-indigo-700' : '' }}">
                                    {{ __('message.Login') }}
                                </a>
                                <a href="{{ route('web.view.register') }}"
                                    class="px-3 py-2 rounded-md text-sm font-medium text-gray-500 hover:text-gray-900 hover:bg-gray-50 {{ request()->routeIs('web.view.register') ? 'text-indigo-700' : '' }}">
                                    {{ __('message.Register') }}
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownButton = document.getElementById('languageDropdownButton');
            const dropdownMenu = document.getElementById('languageDropdown');

            dropdownButton?.addEventListener('click', function (e) {
                e.stopPropagation();
                dropdownMenu.classList.toggle('show');
            });

            document.addEventListener('click', function () {
                dropdownMenu.classList.remove('show');
            });

            dropdownMenu?.addEventListener('click', function (e) {
                e.stopPropagation();
            });

            setTimeout(function () {
                document.querySelectorAll('.alert-message').forEach(function (el) {
                    el.remove();
                });
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>

</html>
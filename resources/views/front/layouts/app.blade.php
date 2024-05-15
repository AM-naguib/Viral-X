<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Code Solutions</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

    <nav style="background-color: rgb(11 21 24)" class="bg-gray-800">
        <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">

            <div class="relative flex h-16 items-center justify-between">
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <!-- Mobile menu button-->
                    <button type="button" id="mobile-menu-button"
                        class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <span class="absolute -inset-0.5"></span>
                        <span class="sr-only">Open main menu</span>
                        <!--
                  Icon when menu is closed.

                  Menu open: "hidden", Menu closed: "block"
                -->
                        <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <!--
                  Icon when menu is open.

                  Menu open: "block", Menu closed: "hidden"
                -->
                        <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex flex-shrink-0 items-center">
                        <a href="{{ route('front.index') }}"><img class="h-8 w-auto"
                                src="{{ asset('assets/img') }}/logo.png" alt="ViralX" /></a>
                    </div>
                    <div class="hidden sm:ml-6 sm:block">
                        <div class="flex space-x-4">
                            <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                            <a href="{{ route('front.index') }}"
                                class="@yield('index') hover:bg-gray-700 text-gray-300 text-white rounded-md px-3 py-2 text-sm font-medium"
                                aria-current="page">Home</a>
                            <a href="{{ route('front.pricing') }}"
                                class="text-gray-300 @yield('pricing') hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Pricing</a>
                            <a href="{{ route('front.contact') }}"
                                class="text-gray-300 @yield('contact') hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Contact</a>
                                <a href="{{ route('front.refund-policy') }}"
                                class="text-gray-300 @yield('refund-policy') hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Refund Policy</a>
                        </div>
                    </div>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">


                    <!-- Profile dropdown -->
                    <div class="relative ml-3 ">

                        @if (Auth::check())
                            <div class="flex items-center rounded-full  text-white cursor-pointer">
                                <p id="user-menu-button">{{ Auth::user()->name }}</p>
                            </div>
                            <div id="user-menus"
                                class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                                tabindex="-1">
                                <!-- Active: "bg-gray-100", Not Active: "" -->
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700"
                                    role="menuitem" tabindex="-1" id="user-menu-item-0">Dashboard</a>
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <button type="submit" class="block px-4 py-2 text-sm text-gray-700" role="menuitem"
                                        tabindex="-1" id="user-menu-item-2">Sign out</button>
                                </form>
                            </div>
                        @else
                            <div class="flex items-center rounded-full   text-white cursor-pointer">
                                <div>
                                    <a href="{{ route('login') }}"
                                        class=" hover:bg-gray-700 text-gray-300 text-white rounded-md px-3 py-2 text-sm font-semibold border-solid border-2 border-purple-900"
                                        aria-current="page">Login</a>
                                    <a href="{{ route('register') }}"
                                        class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-semibold border-solid border-2 border-purple-900">
                                        Sign Up
                                    </a>
                                </div>
                            </div>
                        @endif
                        <script>
                            let userMenuButton = document.querySelector('#user-menu-button');
                            userMenuButton.addEventListener('click', () => {
                                document.querySelector('#user-menus').classList.toggle('hidden');
                            })
                        </script>

                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state. -->
        <div class="sm:hidden hidden" id="mobile-menu">
            <div class="space-y-1 px-2 pb-3 pt-2">
                <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                <a href="{{ route('front.index') }}"
                    class="@yield('index') text-gray-300 block hover:bg-gray-700 rounded-md px-3 py-2 text-base font-medium"
                    aria-current="page">Home</a>
                <a href="{{ route('front.pricing') }}"
                    class="text-gray-300 @yield('pricing') hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium">Pricing</a>
                <a href="{{ route('front.contact') }}"
                    class="text-gray-300 @yield('contact') hover:bg-gray-700 hover:text-white block rounded-md px-3 py-2 text-base font-medium">Contact</a>
            </div>
        </div>
    </nav>
    <script>
        let mobileMenuButton = document.querySelector('#mobile-menu-button');
        let mobileMenu = document.querySelector('#mobile-menu');
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        })
    </script>
    @yield('content')
</body>

</html>

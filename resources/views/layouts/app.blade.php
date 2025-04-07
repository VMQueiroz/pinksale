<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <!-- Alpine Function -->
    <script>
        function appLayout() {
            return {
                isCollapsed: window.innerWidth < 768 ? true : false,
                isMobileMenuOpen: false,
                toggleSidebarDesktop() {
                    this.isCollapsed = !this.isCollapsed;
                },
                toggleSidebarMobile() {
                    this.isMobileMenuOpen = !this.isMobileMenuOpen;
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-100">
<x-banner />

<div x-data="appLayout()" x-cloak class="flex min-h-screen">

    <!-- Sidebar -->
    <aside
        :class="{
            'w-64': !isCollapsed,
            'w-20': isCollapsed,
            'translate-x-0 shadow-lg': isMobileMenuOpen,
            '-translate-x-full': !isMobileMenuOpen
        }"
        class="fixed inset-y-0 left-0 z-40 bg-white text-pk transition-all duration-300 transform md:relative md:translate-x-0 md:flex md:flex-col flex-shrink-0 border-r border-gray-200"
        aria-label="Sidebar">

        <!-- Logo Area -->
        <div class="flex items-center justify-center h-16 flex-shrink-0 px-4 border-b border-gray-200" :class="isCollapsed ? 'justify-center' : 'justify-start'">
            {{-- Logos change based on isCollapsed state. Ensure files exist in public/img/ --}}
            <img :src="isCollapsed ? '/img/logo-pinksale-icon.png' : '/img/logo-pinksale-full.png'"
                 alt="Logo PinkSale"
                 :class="isCollapsed ? 'h-12' : 'h-14'" {{-- Dynamic height based on isCollapsed --}}
                 class="transition-all duration-300">
        </div>

        <!-- Navigation -->
        <nav class="flex-1 space-y-1">
            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}" 
               class="flex items-center p-2 rounded-md hover:bg-pk hover:text-white {{ request()->routeIs('dashboard') ? 'bg-pk text-white' : 'text-pk' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span x-show="!isCollapsed" class="ml-3">Dashboard</span>
            </a>

            {{-- Clientes e Parceiros --}}
            <div x-data="{ open: false }" class="relative">
                <!-- Botão do menu -->
                <button @click="open = !open" 
                        :class="{
                            'z-[60]': open && isCollapsed,
                            'bg-pk text-white': {{ request()->routeIs(['clientes.*', 'parceiros.*', 'sessoes.*', 'urnas.*', 'consultores.*', 'aniversariantes.*']) ? 'true' : 'false' }}
                        }"
                        class="flex items-center w-full p-2 rounded-md hover:bg-pk hover:text-white relative text-pk">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span x-show="!isCollapsed" class="ml-3">Clientes e Parceiros</span>
                    <svg x-show="!isCollapsed" class="w-3 h-3 ml-auto" :class="{'rotate-180': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                
                <!-- Submenu -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     :class="[
                         isCollapsed ? 'absolute left-full top-0 ml-1 w-48 z-[55]' : 'relative w-full mt-1',
                         'origin-top-right bg-white rounded-md shadow-lg'
                     ]">
                    <div class="py-1">
                        <a href="#" class="block px-4 py-2 text-sm text-pk hover:bg-pk hover:text-white">Abordagens</a>
                        <a href="{{ route('clientes.index') }}" class="block px-4 py-2 text-sm text-pk hover:bg-pk hover:text-white">Clientes</a>
                        <a href="{{ route('parceiros.index') }}" class="block px-4 py-2 text-sm text-pk hover:bg-pk hover:text-white">Parceiros</a>
                        <a href="#" class="block px-4 py-2 text-sm text-pk hover:bg-pk hover:text-white">Sessões</a>
                        <a href="#" class="block px-4 py-2 text-sm text-pk hover:bg-pk hover:text-white">Urnas</a>
                        <a href="{{ route('consultores.index') }}" class="block px-4 py-2 text-sm text-pk hover:bg-pk hover:text-white">Consultores</a>
                        <a href="#" class="block px-4 py-2 text-sm text-pk hover:bg-pk hover:text-white">Aniversariantes</a>
                    </div>
                </div>
            </div>

            {{-- Produtos --}}
            <a href="{{ route('produtos.index') }}" 
               class="flex items-center p-2 rounded-md hover:bg-pk hover:text-white {{ request()->routeIs('produtos.*') ? 'bg-pk text-white' : 'text-pk' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span x-show="!isCollapsed" class="ml-3">Produtos</span>
            </a>

            {{-- Vendas --}}
            <a href="{{ route('vendas.index') }}" 
               class="flex items-center p-2 rounded-md hover:bg-pk hover:text-white {{ request()->routeIs('vendas.*') ? 'bg-pk text-white' : 'text-pk' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span x-show="!isCollapsed" class="ml-3">Vendas</span>
            </a>
        </nav>

        <!-- Logout / User Area -->
        <div class="p-2 mt-auto border-t border-gray-200">
            {{-- Placeholder: Implement correct logout mechanism --}}
            <form method="POST" action="{{ route('logout') }}" x-data>
                @csrf
                <button type="submit" title="Sair"
                        class="flex items-center justify-center w-full p-2 rounded-md text-pk hover:bg-pk hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span x-show="!isCollapsed" class="ml-3 whitespace-nowrap">Sair</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Overlay for mobile -->
    <div x-show="isMobileMenuOpen" @click="isMobileMenuOpen = false"
         class="fixed inset-0 z-30 bg-black opacity-50 md:hidden" x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"></div>

    <!-- Main Content Area -->
    <div class="flex flex-col flex-1 transition-all duration-300 pt-16" class="md:ml-10">
        <!-- Header -->
        <header class="fixed top-0 left-0 right-0 z-50 h-16 bg-pk text-white shadow-md flex items-center justify-between px-4" :class="isCollapsed ? 'md:left-20' : 'md:left-64'">
            <div class="flex items-center">
                <!-- Mobile Menu Toggle -->
                <button @click="toggleSidebarMobile()" class="text-white focus:outline-none md:hidden mr-4">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Desktop Sidebar Toggle -->
                <button @click="toggleSidebarDesktop()" class="hidden md:block text-white focus:outline-none">
                    {{-- Replaced arrows with hamburger icon --}}
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Header Content Slot -->
            <div class="flex-1 ml-4">
                @if (isset($header))
                    <h1 class="text-xl font-semibold text-white">{{ $header }}</h1>
                @endif
            </div>

            <!-- User menu or other header items -->
            <div>
                {{-- Add user dropdown or other header elements here if needed --}}
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-6">
            {{ $slot }}
        </main>
    </div>

</div>

@stack('modals')
@livewireScripts
</body>
</html>















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

    <!-- Sticky Header CSS -->
    <link href="{{ asset('css/sticky-header.css') }}" rel="stylesheet">

    <!-- Calendar CSS -->
    <link href="{{ asset('css/calendar.css') }}" rel="stylesheet">

    <style>
        [x-cloak] { display: none !important; }

        /* Estilos para o submenu */
        .sidebar-content.w-20 ~ div .submenu-outside {
            position: fixed !important;
            left: 80px !important;
            z-index: 9999 !important;
            width: 200px !important;
        }

        /* Garantir que o submenu seja exibido corretamente quando a sidebar está colapsada */
        @media (min-width: 768px) {
            .sidebar-content.w-20 + div #clientes-parceiros-submenu,
            .sidebar-content.w-20 #clientes-parceiros-submenu {
                position: fixed !important;
                left: 80px !important;
                z-index: 99999 !important;
            }

            /* Estilo para o submenu quando a sidebar está expandida */
            .sidebar-content:not(.w-20) #clientes-parceiros-submenu {
                width: 100% !important;
                position: relative !important;
                margin-top: 0.25rem !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
                left: 0 !important;
                right: 0 !important;
            }

            /* Garantir que os links do submenu ocupem toda a largura disponível */
            .sidebar-content:not(.w-20) #clientes-parceiros-submenu a {
                width: 100% !important;
                display: block !important;
            }
        }

        /* Garantir que o submenu fique na frente do conteúdo principal */
        #clientes-parceiros-submenu {
            z-index: 99999 !important;
        }

        /* Ajustar o conteúdo principal para ficar atrás do submenu */
        .main-content {
            z-index: 1;
            position: relative;
        }
    </style>

    <!-- Alpine Function -->
    <script>
        function appLayout() {
            return {
                isCollapsed: window.innerWidth < 768 ? true : false,
                isMobileMenuOpen: false,
                subMenuOpen: null, // Rastrear qual submenu está aberto
                init() {
                    // Verificar se estamos em uma rota que pertence a um submenu
                    const isInClientesParceirosPaths = {{ request()->routeIs(['clientes.*', 'parceiros.*', 'sessoes.*', 'urnas.*', 'consultores.*', 'aniversariantes.*', 'abordagens.*']) ? 'true' : 'false' }};

                    // Se estamos em uma rota de submenu e a sidebar está colapsada, abrir o submenu automaticamente
                    if (isInClientesParceirosPaths && this.isCollapsed) {
                        this.subMenuOpen = 'clientes-parceiros';
                    }

                    // Adicionar listener para redimensionamento da janela
                    window.addEventListener('resize', () => {
                        if (window.innerWidth < 768) {
                            this.isCollapsed = true;
                        }
                    });
                },
                toggleSidebarDesktop() {
                    // Guardar o estado atual do submenu
                    const currentSubmenuState = this.subMenuOpen;

                    // Alternar o estado da sidebar
                    this.isCollapsed = !this.isCollapsed;

                    // Fechar o submenu quando a sidebar é colapsada ou expandida
                    this.subMenuOpen = null;
                },
                toggleSidebarMobile() {
                    this.isMobileMenuOpen = !this.isMobileMenuOpen;
                },
                toggleSubmenu(name) {
                    // Se clicar no mesmo submenu, alterna entre aberto/fechado
                    if (this.subMenuOpen === name) {
                        this.subMenuOpen = null;
                    } else {
                        this.subMenuOpen = name;
                    }
                },
                isSubmenuOpen(name) {
                    return this.subMenuOpen === name;
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-100 overflow-hidden">
<x-banner />

<div x-data="appLayout()" x-init="init()" @click.away="subMenuOpen = null" x-cloak class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside
        :class="{
            'w-64': !isCollapsed,
            'w-20': isCollapsed,
            'translate-x-0 shadow-lg': isMobileMenuOpen,
            '-translate-x-full': !isMobileMenuOpen
        }"
        class="fixed inset-y-0 left-0 z-40 bg-white text-pk transition-all duration-300 transform md:relative md:translate-x-0 md:flex md:flex-col flex-shrink-0 border-r border-gray-200 sidebar-content"
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
            <div class="relative w-full">
                <!-- Botão do menu -->
                <button id="clientes-parceiros-btn" x-ref="clientesBtn" @click="toggleSubmenu('clientes-parceiros'); $event.stopPropagation();"
                        class="flex items-center w-full p-2 rounded-md hover:bg-pk hover:text-white relative text-pk"
                        :class="{
                            'bg-pk text-white': {{ request()->routeIs(['clientes.*', 'parceiros.*', 'sessoes.*', 'urnas.*', 'consultores.*', 'aniversariantes.*', 'abordagens.*']) ? 'true' : 'false' }}
                        }">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span x-show="!isCollapsed" class="ml-3">Clientes e Parceiros</span>
                    <svg x-show="!isCollapsed" class="w-3 h-3 ml-auto" :class="{'rotate-180': isSubmenuOpen('clientes-parceiros')}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <!-- Submenu -->
                <div id="clientes-parceiros-submenu"
                     x-show="isSubmenuOpen('clientes-parceiros')"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     x-cloak
                     class="bg-white rounded-md shadow-lg py-1 overflow-hidden border border-gray-200"
                     :class="isCollapsed ? 'fixed left-20 top-0 w-48 z-[99999]' : 'relative w-full mt-1'"
                     :style="isCollapsed ? 'top: ' + $refs.clientesBtn.getBoundingClientRect().top + 'px;' : ''"
                     @click.away="subMenuOpen = null">

                    <!-- Adicionar uma seta para o submenu quando colapsado -->
                    <div x-show="isCollapsed" class="absolute top-3 -left-2 w-0 h-0 border-t-[6px] border-t-transparent border-r-[6px] border-r-white border-b-[6px] border-b-transparent"></div>
                    <div class="py-1 w-full" :class="{ 'px-0': !isCollapsed, 'px-1': isCollapsed }">
                        <a href="{{ route('abordagens.index') }}" class="block px-4 py-2 text-sm text-pk hover:bg-pk hover:text-white {{ request()->routeIs('abordagens.*') ? 'bg-pk text-white' : 'text-pk' }}">Abordagens</a>
                        <a href="{{ route('clientes.index') }}" class="block px-4 py-2 text-sm text-pk hover:bg-pk hover:text-white {{ request()->routeIs('clientes.*') ? 'bg-pk text-white' : 'text-pk' }}">Clientes</a>
                        <a href="{{ route('parceiros.index') }}" class="block px-4 py-2 text-sm text-pk hover:bg-pk hover:text-white {{ request()->routeIs('parceiros.*') ? 'bg-pk text-white' : 'text-pk' }}">Parceiros</a>
                        <a href="#" class="block px-4 py-2 text-sm text-pk hover:bg-pk hover:text-white {{ request()->routeIs('sessoes.*') ? 'bg-pk text-white' : 'text-pk' }}">Sessões</a>
                        <a href="#" class="block px-4 py-2 text-sm text-pk hover:bg-pk hover:text-white {{ request()->routeIs('urnas.*') ? 'bg-pk text-white' : 'text-pk' }}">Urnas</a>
                        <a href="{{ route('consultores.index') }}" class="block px-4 py-2 text-sm text-pk hover:bg-pk hover:text-white {{ request()->routeIs('consultores.*') ? 'bg-pk text-white' : 'text-pk' }}">Consultores</a>
                        <a href="#" class="block px-4 py-2 text-sm text-pk hover:bg-pk hover:text-white {{ request()->routeIs('aniversariantes.*') ? 'bg-pk text-white' : 'text-pk' }}">Aniversariantes</a>
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

            {{-- Agenda --}}
            <a href="{{ route('agenda.index') }}"
               class="flex items-center p-2 rounded-md hover:bg-pk hover:text-white {{ request()->routeIs('agenda.*') ? 'bg-pk text-white' : 'text-pk' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span x-show="!isCollapsed" class="ml-3">Agenda</span>
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
    <div class="flex flex-col flex-1 transition-all duration-300 h-screen overflow-hidden">
        <!-- Header -->
        <header class="sticky top-0 z-50 h-16 bg-pk text-white shadow-md flex items-center justify-between px-4">
            <div class="flex items-center">
                <!-- Mobile Menu Toggle -->
                <button @click="toggleSidebarMobile()" class="text-white focus:outline-none md:hidden mr-4">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Desktop Sidebar Toggle -->
                <button @click="toggleSidebarDesktop(); subMenuOpen = null;" class="hidden md:block text-white focus:outline-none">
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
        <main class="flex-1 p-6 main-content">
            {{ $slot }}
        </main>
    </div>

</div>

@stack('modals')
@livewireScripts

<!-- Script para garantir que o submenu seja exibido corretamente -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar se estamos em uma rota que pertence ao submenu Clientes e Parceiros
        const isInClientesParceirosPaths = {{ request()->routeIs(['clientes.*', 'parceiros.*', 'sessoes.*', 'urnas.*', 'consultores.*', 'aniversariantes.*', 'abordagens.*']) ? 'true' : 'false' }};

        // Verificar se a sidebar está colapsada
        const sidebarIsCollapsed = window.innerWidth < 768 || document.querySelector('.sidebar-content').classList.contains('w-20');

        // Função para garantir que o submenu fique na frente
        function ensureSubmenuOnTop() {
            const submenu = document.getElementById('clientes-parceiros-submenu');
            const sidebar = document.querySelector('.sidebar-content');
            const isCollapsed = sidebar && sidebar.classList.contains('w-20');

            if (submenu) {
                if (isCollapsed) {
                    // Quando a sidebar está colapsada, mover o submenu para o final do body
                    document.body.appendChild(submenu);
                    submenu.style.zIndex = '99999';
                    submenu.style.position = 'fixed';

                    // Posicionar o submenu corretamente
                    const submenuButton = document.getElementById('clientes-parceiros-btn');
                    if (submenuButton) {
                        const buttonRect = submenuButton.getBoundingClientRect();
                        submenu.style.left = '80px';
                        submenu.style.top = buttonRect.top + 'px';
                        submenu.style.width = '200px';
                    }
                } else {
                    // Quando a sidebar está expandida, garantir que o submenu esteja dentro da sidebar
                    const submenuParent = document.querySelector('.relative.w-full');
                    if (submenuParent && !submenuParent.contains(submenu)) {
                        submenuParent.appendChild(submenu);
                    }

                    // Resetar os estilos inline
                    submenu.style.position = 'relative';
                    submenu.style.left = '0';
                    submenu.style.top = '0';
                    submenu.style.width = '100%';
                    submenu.style.zIndex = '1';
                    submenu.style.marginTop = '0.25rem';
                }
            }
        }

        // Se estamos em uma rota de submenu e a sidebar está colapsada, abrir o submenu automaticamente
        if (isInClientesParceirosPaths && sidebarIsCollapsed) {
            // Encontrar o botão do submenu pelo ID
            const submenuButton = document.getElementById('clientes-parceiros-btn');

            // Simular um clique no botão para abrir o submenu
            if (submenuButton) {
                setTimeout(() => {
                    submenuButton.click();
                    // Garantir que o submenu fique na frente
                    setTimeout(ensureSubmenuOnTop, 100);
                }, 500);
            }
        }

        // Adicionar listener para cliques no botão do submenu
        const submenuButton = document.getElementById('clientes-parceiros-btn');
        if (submenuButton) {
            submenuButton.addEventListener('click', function() {
                // Garantir que o submenu fique na frente
                setTimeout(ensureSubmenuOnTop, 100);
            });
        }

        // Adicionar listener para o evento de alternar a sidebar
        const sidebarToggleButtons = document.querySelectorAll('button.hidden.md\\:block');
        sidebarToggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Verificar se o submenu está aberto
                const submenu = document.getElementById('clientes-parceiros-submenu');
                if (submenu && submenu.style.display !== 'none') {
                    // Aguardar a transição da sidebar e reposicionar o submenu
                    setTimeout(ensureSubmenuOnTop, 300);
                }
            });
        });

        // Adicionar listener para o botão de colapsar a sidebar
        const sidebarToggleButton = document.querySelector('button.hidden.md\\:block');
        if (sidebarToggleButton) {
            sidebarToggleButton.addEventListener('click', function() {
                // Fechar o submenu quando a sidebar é colapsada ou expandida
                const submenu = document.getElementById('clientes-parceiros-submenu');
                if (submenu) {
                    submenu.style.display = 'none';
                }
            });
        }

        // Adicionar listener para cliques no documento para fechar o submenu
        document.addEventListener('click', function(event) {
            const submenu = document.getElementById('clientes-parceiros-submenu');
            const submenuButton = document.getElementById('clientes-parceiros-btn');

            // Se o clique não foi no submenu nem no botão do submenu, fechar o submenu
            if (submenu && submenuButton &&
                !submenu.contains(event.target) &&
                !submenuButton.contains(event.target)) {

                // Fechar o submenu usando Alpine.js
                const appElement = document.querySelector('[x-data]');
                if (appElement && appElement.__x) {
                    appElement.__x.$data.subMenuOpen = null;
                }

                // Também esconder o submenu diretamente via DOM
                submenu.style.display = 'none';
            }
        });
    });
</script>
</body>
</html>















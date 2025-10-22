<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Huda ERP')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/luxury-style.css') }}" rel="stylesheet">
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Ensure content doesn't go behind sidebar */
        .main-content {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            width: 100%;
            max-width: 100%;
        }
        
        /* Container wrapper - full width after sidebar */
        .container-wrapper {
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
        }
        
        /* Sidebar overlay for mobile */
        @media (max-width: 768px) {
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 40;
            }
        }
        
        /* Desktop layout - content starts after sidebar */
        @media (min-width: 1024px) {
            .main-content {
                margin-left: 16rem; /* Start after sidebar */
                width: calc(100vw - 16rem);
            }
        }
        
        /* Mobile layout - full width */
        @media (max-width: 1023px) {
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: false }">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0" 
         :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
        @include('layouts.partials.sidebar')
    </div>

    <!-- Overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-black bg-opacity-50" x-cloak></div>

    <!-- Main Content -->
    <div class="flex-1 main-content content-wrapper">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b relative z-10" style="border-bottom: 1px solid #e5e5e5;">
            <div class="flex items-center justify-between h-16 px-6">
                <div class="flex items-center">
                    <button @click="sidebarOpen = true" class="text-gray-600 hover:text-gray-900 lg:hidden">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="ml-4 text-xl font-bold text-gray-800" style="font-family: 'Inter', sans-serif; letter-spacing: -0.5px;">
                        @yield('page-title', 'Dashboard')
                    </h1>
                </div>
                <div class="flex items-center space-x-6">
                    <!-- Notifications -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center" style="font-size: 0.65rem;">
                                3
                            </span>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
                             style="box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="font-bold text-gray-800">Notifications</h3>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                    <div class="flex items-start">
                                        <div class="w-2 h-2 bg-red-500 rounded-full mt-2 mr-3"></div>
                                        <div>
                                            <p class="text-sm text-gray-800 font-medium">Low Stock Alert</p>
                                            <p class="text-xs text-gray-600 mt-1">Material "Cotton Fabric" is running low</p>
                                            <p class="text-xs text-gray-400 mt-1">2 hours ago</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                    <div class="flex items-start">
                                        <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2 mr-3"></div>
                                        <div>
                                            <p class="text-sm text-gray-800 font-medium">New Order</p>
                                            <p class="text-xs text-gray-600 mt-1">Order #ORD-12345 has been placed</p>
                                            <p class="text-xs text-gray-400 mt-1">5 hours ago</p>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                    <div class="flex items-start">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3"></div>
                                        <div>
                                            <p class="text-sm text-gray-800 font-medium">Production Completed</p>
                                            <p class="text-xs text-gray-600 mt-1">Production order PRO-789 is completed</p>
                                            <p class="text-xs text-gray-400 mt-1">1 day ago</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="p-3 border-t border-gray-200 text-center">
                                <a href="#" class="text-sm font-medium" style="color: #d4af37;">View All Notifications</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Menu -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-3 text-gray-700 hover:text-gray-900">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-white" 
                                 style="background: linear-gradient(135deg, #1a1a1a 0%, #d4af37 100%);">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-semibold">{{ auth()->user()->name ?? 'User' }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role ?? 'user') }}</p>
                            </div>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
                             style="box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
                            <a href="{{ route('users.show', auth()->user()->id ?? 1) }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-user w-5 mr-3" style="color: #d4af37;"></i>
                                My Profile
                            </a>
                            <a href="{{ route('settings') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-cog w-5 mr-3" style="color: #d4af37;"></i>
                                Settings
                            </a>
                            <div class="border-t border-gray-200 my-2"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="relative z-0" style="background: #f8f8f8;">
            <div class="container-wrapper">
                <div class="p-4 md:p-6">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     class="mb-4 px-6 py-4 rounded-lg" 
                     style="background: #fff; border-left: 4px solid #28a745; box-shadow: 0 2px 12px rgba(0,0,0,0.08);">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4" 
                             style="background: rgba(40, 167, 69, 0.1);">
                            <i class="fas fa-check-circle text-xl" style="color: #28a745;"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Success!</p>
                            <p class="text-sm text-gray-600">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     class="mb-4 px-6 py-4 rounded-lg"
                     style="background: #fff; border-left: 4px solid #dc3545; box-shadow: 0 2px 12px rgba(0,0,0,0.08);">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4"
                             style="background: rgba(220, 53, 69, 0.1);">
                            <i class="fas fa-exclamation-circle text-xl" style="color: #dc3545;"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Error!</p>
                            <p class="text-sm text-gray-600">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
                </div>
            </div>
        </main>
    </div>
</body>
</html>
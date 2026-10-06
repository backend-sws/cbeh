<header class="fixed top-0 left-0 md:left-64 w-full md:w-[calc(100%-16rem)] bg-white/80 backdrop-blur-lg border-b border-gray-100 shadow-sm p-3 md:p-4 flex justify-between items-center z-50 transition-all duration-300">    <!-- Left Side: Logo and Sidebar Toggle -->
    <div class="flex items-center space-x-2 md:space-x-4">
        <!-- Desktop Sidebar Toggle and Dashboard Title -->
        <div class="hidden md:flex items-center">
            <button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-pink-100">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <h2 class="text-xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent ml-3 tracking-tight">Dashboard</h2>
        </div>

        <!-- Mobile View Sidebar Toggle -->
        <div class="flex md:hidden items-center">
            <button onclick="toggleSidebar()" class="p-2 rounded-lg hover:bg-gray-100 text-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-pink-100">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <h2 class="text-lg font-bold text-gray-800 ml-2 tracking-tight">HMS</h2>
        </div>
    </div>

    <!-- Right Side: Search, Notifications, and User Menu -->
    <div class="flex items-center space-x-4">
        <!-- Search Bar (Desktop Only) -->
        {{-- <div class="hidden md:block relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="search" class="pl-10 pr-4 py-2 w-64 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Search...">
        </div> --}}

        <!-- Notifications -->
        {{-- <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="p-2 rounded-full hover:bg-gray-100 transition-colors relative">
                <i class="fas fa-bell text-gray-600 text-lg"></i>
                <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500"></span>
            </button>
            
            <!-- Notifications Dropdown -->
            <div x-show="open" @click.away="open = false" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-72 bg-white rounded-md shadow-lg border z-50 py-1">
                <div class="px-4 py-2 border-b border-gray-200">
                    <h3 class="text-sm font-medium text-gray-700">Notifications</h3>
                </div>
                <div class="max-h-60 overflow-y-auto">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">New appointment scheduled</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Patient checkup reminder</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">System update available</a>
                </div>
                <div class="px-4 py-2 border-t border-gray-200 text-center">
                    <a href="#" class="text-xs text-blue-600 hover:underline">View all notifications</a>
                </div>
            </div>
        </div> --}}

        <!-- User Profile -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center space-x-3 p-1 pr-3 bg-white border border-gray-200 rounded-full shadow-sm hover:shadow focus:outline-none transition-shadow">
                <div class="h-8 w-8 rounded-full overflow-hidden border border-gray-100">
                    @if(auth()->user()->image)
                        <img src="{{ asset('avatar/' . auth()->user()->image) }}" alt="User" class="h-full w-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" alt="User" class="h-full w-full object-cover">
                    @endif
                </div>
                <span class="hidden md:inline-block text-sm font-semibold text-gray-700 tracking-wide">{{ auth()->user()->name }}</span>
                <i class="fas fa-chevron-down hidden md:inline-block text-xs text-gray-400"></i>
            </button>
            
            <!-- Profile Dropdown -->
            <div x-show="open" @click.away="open = false" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                 x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="transform opacity-0 scale-95 translate-y-2"
                 class="absolute right-0 mt-3 w-56 bg-white/95 backdrop-blur-sm rounded-xl shadow-xl border border-gray-100 z-50 py-2 divide-y divide-gray-50">
                {{-- <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-user-circle mr-2"></i> Profile
                </a> --}}
                <a href="{{ route('admin.hospital-web-settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-cog mr-2"></i> Settings
                </a>
                <div class="border-t border-gray-200"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-sign-out-alt mr-2"></i> Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
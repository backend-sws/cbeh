<header class="bg-white text-black p-4 flex justify-between items-center shadow-md">
    <!-- Left Side: Toggle Button, Logo, and Page Title -->
    <div class="flex items-center space-x-4">
    
<div class="w-64 flex items-center">
        <!-- Logo -->
        <img src="{{asset('img/site-logo.png')}}" alt="Logo" class="h-10">
        <!-- Page Title -->
        <h3 class="font-bold items-center text-2xl ml-3">HMS</h3>
    </div>
         <i class="fa fa-bars" aria-hidden="true"></i>
        <h1 class="text-xl font-semibold">Admin Dashboard</h1>
    </div>

    <!-- Right Side: Notification, Settings, and User Icons -->
    <div class="flex items-center space-x-6">

        <div class="flex items-center rounded-[20px]">
            <input class="form-control p-1 pl-[12px] w-full rounded-l-[20px] focus:outline-none focus:ring-2 focus:ring-blue-500 bg-[#D9D9D9] border-none" type="search" placeholder="Search" aria-label="Search">
            <button class="bg-brandPurple text-white px-4 py-1 rounded-r-[20px] hover:bg-brandPurple focus:outline-none">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <!-- Notification Icon -->
        <button class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-bell"></i>
        </button>

        <!-- Settings Icon -->
        <button class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-cog"></i>
        </button>

        <!-- User Icon (Profile Picture) -->
        <div class="relative">
            <button id="userProfileButton" class="h-10 w-10 rounded-full overflow-hidden border-2 border-gray-300">
                <img src="{{asset('img/default_user.png')}}" alt="User" class="h-full w-full object-cover">
            </button>

            <!-- Dropdown Menu (Initially Hidden) -->
            <div id="userDropdown" class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-md border border-brandGray hidden">
                <ul class="space-y-2 p-2">
                    <li>
                        <a href="#" class="text-gray-700 hover:bg-gray-100 p-2 rounded flex items-center space-x-2">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

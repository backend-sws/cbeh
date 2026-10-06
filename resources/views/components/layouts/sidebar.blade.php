@php

use \Spatie\Permission\Models\Role;

$rolesSubmenu = [];
foreach (Role::all() as $role) {
    $rolesSubmenu[] = [
        'title' => ucwords($role->name),
        'link' => route('admin.users.user_list',['name' =>  $role->name]), 
        'is_active' => Request::is('admin/user/'.$role->name),
        'permission' => 'user_list',
    ];
}

    $sidebar_menu_links = [
        [
            'title' => 'Dashboard',
            'icon' => <<<HTML
            <i class="nav-icon fas fa-tachometer-alt"></i>
            HTML,
            'link' => route('admin.dashboard'),
            'is_active' =>Request::is('admin/dashboard'),
            'submenu' => [],
            'permission' => 'dashboard',
        ],
        [
            'title' => 'Users',
            'icon' => <<<HTML
            <i class="nav-icon fas fa-users"></i>
            HTML,
            'link' => '',
            'is_active' => Request::is('admin/user/*'),
            'submenu' => $rolesSubmenu,
            'permission' => 'users',
        ],
    ];
@endphp

 
 <!-- Sidebar (Hidden/Shown) -->
  <div id="sidebar"
      class="w-64 bg-white-800 text-gray p-4 transition-transform duration-300 transform -translate-x-full lg:translate-x-0 lg:w-64 shadow-md shadow-right">
      <!-- <h2 class="text-2xl font-semibold mb-4">Admin Panel</h2> -->


      @if (!empty($sidebar_menu_links))
    <ul class="space-y-4">
        @foreach ($sidebar_menu_links as $sidebar_menu_link)
            @can($sidebar_menu_link['permission'])
                <li class="relative group">
                    <a href="{{ $sidebar_menu_link['link'] }}"
                        class="p-2 hover:bg-brandPink hover:text-brandMagenta  rounded-r-[20px] w-full block flex items-center space-x-2
                        {{ $sidebar_menu_link['is_active'] ? 'bg-brandPink text-brandMagenta' : 'text-[#7A7A7A]' }}">
                        {!! $sidebar_menu_link['icon'] !!}
                        <span>{{ $sidebar_menu_link['title'] }}</span>
                    </a>
                    
                    @if (!empty($sidebar_menu_link['submenu']))
                        <ul class="space-y-2 mt-2 ml-4 {{ $sidebar_menu_link['is_active'] ? 'block' : 'hidden' }}">
                            @foreach ($sidebar_menu_link['submenu'] as $submenu)
                                @can($submenu['permission'])
                                    <li class="relative group">
                                        <a href="{{ $submenu['link'] }}"
                                            class="p-2 hover:bg-brandPink hover:text-brandMagenta  rounded-r-[20px] w-full block flex items-center space-x-2
                                            {{ $submenu['is_active'] ? 'bg-brandPink text-brandMagenta' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <span>{{ $submenu['title'] }}</span>
                                        </a>
                                    </li>
                                @endcan
                            @endforeach
                        </ul>
                    @endif
                    <div class="absolute top-0 left-[-16px] h-full w-[20px] bg-brandPink  group-hover:block {{ $sidebar_menu_link['is_active'] ? 'block' : 'hidden' }}"></div>
                </li>
            @endcan
        @endforeach

        <!-- Logout Item -->
        <li class="relative group">
            <button wire:click="logout" class="w-full text-start">
                <a class="p-2 hover:bg-brandPink hover:text-brandMagenta text-[#7A7A7A] rounded-r-[20px] w-full block flex items-center space-x-2"><i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                </a>
            </button>           
        </li>
    </ul>
@endif
      {{--<ul class="space-y-4">
          <!-- Dashboard Item with Icon -->
          <li class="relative group">
              <a href="#" class="p-2 hover:bg-brandPink hover:text-brandMagenta  rounded rounded-r-[20px] w-full block flex items-center space-x-2 
                {{ Request::is('admin/dashboard') ? 'bg-brandPink text-brandMagenta' : 'text-[#7A7A7A]' }}">
                  <!-- Dashboard Icon -->
                  <i class="fas fa-tachometer-alt"></i>
                  <span>Dashboard</span>
              </a>
              <!-- Left border on hover -->
              <div class="absolute top-0 left-[-16px] h-full w-[20px] bg-brandPink  group-hover:block {{ Request::is('admin/dashboard') ? 'bg-brandPink block' : 'hidden' }}"></div>
          </li>

          <!-- Users Item with Icon -->
          <li class="relative group">
              <a href="#" class="p-2 hover:bg-brandPink hover:text-brandMagenta text-[#7A7A7A] rounded rounded-r-[20px] w-full block flex items-center space-x-2
                {{ Request::is('admin/users/*') ? 'bg-brandPink text-brandMagenta' : '' }}">
                  <!-- User Icon -->
                  <i class="fas fa-users"></i>
                  <span>Users</span>
              </a>
              <!-- Left border on hover -->
              <div class="absolute top-0 left-[-16px] h-full w-[20px] bg-brandPink hidden group-hover:block"></div>
          </li>
      </ul>--}}

  </div>
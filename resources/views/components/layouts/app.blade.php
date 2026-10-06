<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Livewire</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
            /* Custom scrollbar for sidebar */
            .sidebar-scrollbar::-webkit-scrollbar {
                width: 4px !important;
            }

            .sidebar-scrollbar::-webkit-scrollbar-thumb {
                background-color: #924AEF;
                border-radius: 4px;
            }

            .sidebar-scrollbar::-webkit-scrollbar-track {
                background-color: #f3f4f6;
                /* light gray */
            }
    </style>

</head>

<body class="bg-gray-100  h-screen overflow-hidden">
    <livewire:layout.header />
    {{--@include("components.layouts.header")--}}
    <div class="flex h-screen">
        {{-- Sidebar --}}
        <livewire:layout.sidebar />

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col bg-brandGray overflow-y-auto sidebar-scrollbar">
            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>



    @livewireScripts

    @stack('js')

    <script>
    // Toggle sidebar visibility
    const toggleButton = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');

    toggleButton.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
    });
    </script>
</body>

</html>
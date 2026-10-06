@extends('layouts.admin')

@section('title', 'Prescriptions')

@section('content')


<body class="bg-gray-50 p-6 font-sans ">

    <div class="flex justify-between items-center mb-4 ">
        <h1 class="text-2xl font-bold text-gray-800">Prescriptions</h1>
        <div class="flex space-x-2">
            <button class="flex items-center customborder border-main-color space-x-2 px-4 py-2 bg-pink-200 text-gray-700 rounded-md hover:bg-gray-300">
                <i class="ph ph-funnel"></i> <!-- Filter Icon -->
            </button>
            <a href="{{route('admin.prescription.add')}}">
            <button class="text-main-color bg-pink-200 customborder border-main-color px-6 py-2 rounded-lg flex items-center" onclick="window.location.href='/newipdpatient'">
                Prescriptions
            </button>
            </a>

            {{-- <a href="#" class="px-4 py-2 bg-pink-300 text-white font-semibold rounded-md hover:bg-pink-600">
                    New IPD Patients
                </a> --}}
        </div>
    </div>

    <body class="bg-white p-10">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 border">PATIENT</th>
                        <th class="px-4 py-3 border">DOCTORS</th>
                        <th class="px-4 py-3 border">ADDED AT</th>
                        <th class="px-4 py-3 border">STATUS</th>
                        <th class="px-4 py-3 border">ACTION</th>
                    </tr>
                </thead>
                <tbody>

                    <!-- Row 1 -->
                    <tr class="bg-gray-50">
                        <td class="px-4 py-4 border">
                            <div class="font-medium text-gray-800">Arvind Kumar</div>
                            <div class="text-gray-500 text-xs">arvind2024@gmail.com</div>
                        </td>
                        <td class="px-4 py-4 border">
                            <div class="font-medium text-gray-800">Arvind Kumar</div>
                            <div class="text-gray-500 text-xs">arvind2024@gmail.com</div>
                        </td>
                        <td class="px-4 py-4 border text-gray-600 text-center">N/A</td>
                        <td class="px-4 py-4 border text-center">
                            <label class="inline-flex relative items-center cursor-pointer">
                                <input type="checkbox" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:bg-purple-700 relative after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                            </label>
                        </td>
                        <td class="px-4 py-4 border flex items-center justify-center space-x-3 text-gray-600">
                            <button class="text-gray-700 dark:text-gray-500 hover:text-green-500 rounded-md p-2 customborder border-main-color">
                                <i class=" fas fa-print "></i>
                                <button class="text-gray-700 dark:text-gray-500 hover:text-green-500 rounded-md p-2 customborder border-main-color">
                                    <i class=" fas fa-eye "></i>
                                </button>

                                <button class="text-gray-700 dark:text-gray-500 hover:text-green-500 rounded-md p-2 customborder border-main-color">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button class="text-gray-700 dark:text-gray-500 hover:text-red-500 rounded-md p-2 customborder border-main-color">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                        </td>
                    </tr>

                    <!-- Row 2 -->
                    <tr class="bg-white">
                        <td class="px-4 py-4 border">
                            <div class="font-medium text-gray-800">Rahul Kumar</div>
                            <div class="text-gray-500 text-xs">rahul4569@gmail.com</div>
                        </td>
                        <td class="px-4 py-4 border">
                            <div class="font-medium text-gray-800">Rahul Kumar</div>
                            <div class="text-gray-500 text-xs">rahul4569@gmail.com</div>
                        </td>
                        <td class="px-4 py-4 border text-gray-600 text-center">N/A</td>
                        <td class="px-4 py-4 border text-center">
                            <label class="inline-flex relative items-center cursor-pointer">
                                <input type="checkbox" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:bg-purple-700 relative after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                            </label>
                        </td>
                        <td class="px-4 py-4 border flex items-center justify-center space-x-3 text-gray-600">
                            <button class="text-gray-700 dark:text-gray-500 hover:text-green-500 rounded-md p-2 customborder border-main-color">
                                <i class=" fas fa-print "></i>
                                <button class="text-gray-700 dark:text-gray-500 hover:text-green-500 rounded-md p-2 customborder border-main-color">
                                    <i class=" fas fa-eye "></i>
                                </button>

                                <button class="text-gray-700 dark:text-gray-500 hover:text-green-500 rounded-md p-2 customborder border-main-color">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button class="text-gray-700 dark:text-gray-500 hover:text-red-500 rounded-md p-2 customborder border-main-color">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                        </td>
                    </tr>

                    <!-- Row 3 -->
                    <tr class="bg-gray-50">
                        <td class="px-4 py-4 border">
                            <div class="font-medium text-gray-800">Prustam Pandit</div>
                            <div class="text-gray-500 text-xs">protabhja46@gmail.com</div>
                        </td>
                        <td class="px-4 py-4 border">
                            <div class="font-medium text-gray-800">Prustam Pandit</div>
                            <div class="text-gray-500 text-xs">protabhja46@gmail.com</div>
                        </td>
                        <td class="px-4 py-4 border text-gray-600 text-center">N/A</td>
                        <td class="px-4 py-4 border text-center">
                            <label class="inline-flex relative items-center cursor-pointer">
                                <input type="checkbox" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:bg-purple-700 relative after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                            </label>
                        </td>
                        <td class="px-4 py-4 border flex items-center justify-center space-x-3 text-gray-600">

                            <button class="text-gray-700 dark:text-gray-500 hover:text-green-500 rounded-md p-2 customborder border-main-color">
                                <i class=" fas fa-print "></i>
                            </button>
                            <button class="text-gray-700 dark:text-gray-500 hover:text-green-500 rounded-md p-2 customborder border-main-color">
                                <i class=" fas fa-eye "></i>
                            </button>

                            <button class="text-gray-700 dark:text-gray-500 hover:text-green-500 rounded-md p-2 customborder border-main-color">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="text-gray-700 dark:text-gray-500 hover:text-red-500 rounded-md p-2 customborder border-main-color">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </body>


    <!-- Pagination -->
    <div class="flex items-center justify-between mt-4 mb-8 text-gray-600 text-sm">
        <div class="flex space-x-1">
            <button class="px-3 py-1 bg-pink-500 text-white rounded">1</button>
            <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-200">2</button>
            <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-200">3</button>
            <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-200">4</button>
            <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-200">5</button>
            <button class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-200">6</button>
        </div>
        <div>
            <span>Show</span>
            <select class="ml-2 border border-gray-300 rounded p-1">
                <option>16</option>
                <option>32</option>
                <option>64</option>
            </select>
        </div>
        <div>
            <span>Showing 1 to 10 of 3000</span>
        </div>
    </div>
    <br>
</body>


@endsection

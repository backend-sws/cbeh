@extends('layouts.app')

@section('title', 'Blood')

@section('content')


<body class="bg-gray-50 p-6 font-sans ">

    <div class="flex justify-between items-center mb-4 ">
        <h1 class="text-2xl font-bold text-gray-800"></h1>
        <div class="flex space-x-2">
            <button class="flex items-center customborder border-main-color space-x-2 px-4 py-2 bg-pink-200 text-gray-700 rounded-md hover:bg-gray-300">
                <i class="ph ph-funnel"></i> <!-- Filter Icon -->
            </button>
            <button  class="text-main-color bg-pink-200 customborder border-main-color px-6 py-2 rounded-lg flex items-center" onclick="window.location.href='/newipdpatient'">
                New charges
            </button>
        </div>
    </div>
    <div class="bg-white shadow overflow-x-auto rounded-lg">

        <body class="bg-white p-8">
            <div class="max-w-5xl mx-auto">

                <body class="bg-gray-100 p-8">
                    <div class="max-w-6xl mx-auto bg-white shadow rounded overflow-x-auto">
                        <table class="min-w-full text-sm text-left border-separate border-spacing-y-2">
                            <thead class="text-gray-500 uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-2">Charge Category </th>
                                    <th class="px-4 py-2">Description </th>
                                    <th class="px-4 py-2">Charge Type </th>
                                    <th class="px-4 py-2">Standard Charge </th>
                                    <th class="px-4 py-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Row 1 -->
                                <tr class="bg-gray-100">
                                    <td class="px-4 py-2">Ajsdljdsd</td>
                                    <td class="px-4 py-2">Ksdbshjb</td>
                                    <td class="px-4 py-2">
                                        <span class="bg-red-200 text-red-800 text-xs font-semibold px-3 py-1 rounded">Procedures</span>
                                    </td>
                                    <td class="px-4 py-2 text-purple-700 font-medium">AR$ 100.00.00</td>
                                    <td class="px-4 py-2 flex space-x-2">
                                        <button class="text-gray-700 dark:text-gray-500 hover:text-green-500 rounded-md p-2 border border-main-color">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button class="text-gray-700 dark:text-gray-500 hover:text-red-500 rounded-md p-2 border border-main-color">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                    </td>
                                </tr>
                                <!-- Row 2 -->
                                <tr class="bg-white">
                                    <td class="px-4 py-2">HGsjknsal</td>
                                    <td class="px-4 py-2">Rahupjasdjhda</td>
                                    <td class="px-4 py-2">
                                        <span class="bg-green-200 text-green-800 text-xs font-semibold px-3 py-1 rounded">Other</span>
                                    </td>
                                    <td class="px-4 py-2 text-purple-700 font-medium">AR$ 1000000</td>
                                    <td class="px-4 py-2 flex space-x-2">
                                        <button class="text-gray-700 dark:text-gray-500 hover:text-green-500 rounded-md p-2 border border-main-color">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button class="text-gray-700 dark:text-gray-500 hover:text-red-500 rounded-md p-2 border border-main-color">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                    </td>
                                </tr>
                                <!-- Row 3 -->
                                <tr class="bg-gray-100">
                                    <td class="px-4 py-2">Lndslnasd</td>
                                    <td class="px-4 py-2">N/A</td>
                                    <td class="px-4 py-2">
                                        <span class="bg-green-200 text-green-800 text-xs font-semibold px-3 py-1 rounded">Other</span>
                                    </td>
                                    <td class="px-4 py-2 text-purple-700 font-medium">AR$ 100.00.00</td>
                                    <td class="px-4 py-2 flex space-x-2">
                                        <button class="text-gray-700 dark:text-gray-500 hover:text-green-500 rounded-md p-2 border border-main-color">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button class="text-gray-700 dark:text-gray-500 hover:text-red-500 rounded-md p-2 border border-main-color">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                    </td>
                                </tr>
                                <!-- Row 4 -->
                                <tr class="bg-white">
                                    <td class="px-4 py-2">Kesnas</td>
                                    <td class="px-4 py-2">N/A</td>
                                    <td class="px-4 py-2">
                                        <span class="bg-blue-200 text-blue-800 text-xs font-semibold px-3 py-1 rounded">Investigations</span>
                                    </td>
                                    <td class="px-4 py-2 text-purple-700 font-medium">AR$ 1000.00.00</td>
                                    <td class="px-4 py-2 flex space-x-2">
                                        <button class="text-gray-700 dark:text-gray-500 hover:text-green-500 rounded-md p-2 border border-main-color">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button class="text-gray-700 dark:text-gray-500 hover:text-red-500 rounded-md p-2 border border-main-color">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </body>

            </div>
        </body>


    </div>
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

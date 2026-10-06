@extends('layouts.admin')

@section('title', 'Manual Billing Payments')

@section('content')

<body class="bg-gray-100 p-8">

    <div class="max-w-7xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-semibold mb-6">Manual Billing Payments</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">Arnold Kumar<br>arnoldkumar2345@gmail.com</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">10:30AM<br>2nd Apr 2016</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">AUD 300.00</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">Ranjit Kumar<br>ranjit456@gmail.com</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">10:30AM<br>2nd Apr 2016</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">AUD 10000.00</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">Anamitra Roy<br>anamitra4545@gmail.com</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Unpaid</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">10:30AM<br>3rd Apr 2016</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">AUD 6000.00</div>
                        </td>
                    </tr>
                    </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center mt-6">
            <div class="flex items-center">
                <button class="bg-gray-200 text-gray-700 px-3 py-1 rounded-md mr-1">&lt;</button>
                <button class="bg-gray-200 text-gray-700 px-3 py-1 rounded-md mr-1">1</button>
                <button class="bg-gray-200 text-gray-700 px-3 py-1 rounded-md mr-1">2</button>
                <button class="bg-gray-200 text-gray-700 px-3 py-1 rounded-md mr-1">3</button>
                <button class="bg-gray-200 text-gray-700 px-3 py-1 rounded-md mr-1">4</button>
                <button class="bg-gray-200 text-gray-700 px-3 py-1 rounded-md mr-1">5</button>
                <button class="bg-gray-200 text-gray-700 px-3 py-1 rounded-md mr-1">6</button>
                <button class="bg-gray-200 text-gray-700 px-3 py-1 rounded-md">&gt;</button>
            </div>
            <div class="flex items-center">
                <span class="text-sm text-gray-700 mr-2">Show</span>
                <select class="border rounded-md px-2 py-1 mr-2">
                    <option>16</option>
                </select>
                <span class="text-sm text-gray-700 mr-2">Showing 1 to 10 of 3000</span>
                <span class="text-sm text-gray-700">3000 - Results</span>
            </div>
        </div>

        <div id="loadingOverlay" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 flex justify-center items-center">
            <div class="bg-white p-6 rounded-lg">
                <p class="text-lg font-semibold">Loading...</p>
            </div>
        </div>
    </div>

    <script>
        // Simulate loading (remove this in your actual implementation)
        setTimeout(() => {
            document.getElementById('loadingOverlay').style.display = 'none';
        }, 2000);
    </script>
</body>

@endsection




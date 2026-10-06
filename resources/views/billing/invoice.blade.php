
@extends('layouts.admin')

@section('title', 'Invoice')

@section('content')

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Invoice</h1>
            <div class="flex space-x-2">
                <button class="bg-gray-200 p-2 rounded-lg customborder">🔍</button>
                <a href="{{ route('billing.newinvoice') }}" class="bg-pink-300 customborder text-white px-4 py-2 rounded-lg">New Invoice</a>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3">INVOICE #</th>
                        <th class="p-3">PATIENT</th>
                        <th class="p-3">STATUS</th>
                        <th class="p-3">INVOICE DATE</th>
                        <th class="p-3">AMOUNT</th>
                        <th class="p-3">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="p-3 text-pink-600">SQUANK</td>
                        <td class="p-3">
                            <div class="font-semibold">Arvind Kumar</div>
                        </td>
                        <td class="p-3">
                            <span class="px-3 py-1 rounded-full text-white bg-red-400">Unpaid</span>
                        </td>
                        <td class="p-3 bg-pink-200 rounded-md text-center">10:30AM<br>2nd Apr, 2026</td>
                        <td class="p-3 text-blue-500">ARS 100000.00</td>
                         <td class="px-4 py-2">
                    <button class="fa fa-edit border p-1"></button>
                    <button class="ml-2 fa fa-trash border p-1"></button>
                </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex justify-between items-center mt-4">
            <div class="flex space-x-2">
                <button class="px-3 py-1 bg-pink-500 text-white rounded">1</button>
                <button class="px-3 py-1 bg-gray-300 rounded">2</button>
                <button class="px-3 py-1 bg-gray-300 rounded">3</button>
                <button class="px-3 py-1 bg-gray-300 rounded">4</button>
                <button class="px-3 py-1 bg-gray-300 rounded">5</button>
            </div>
            <div class="text-gray-500">Showing 1 to 10 of 3000 Results</div>
        </div>
    </div>
</body>

@endsection

@extends('layouts.admin')

@section('title', 'Billing')

@section('content')


<body class="bg-gray-100 p-6
">
    <div class="max-w-6xl mx-auto bg-white p-6 shadow-md rounded-lg">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">New Bill</h1>
            <a href="#" class="bg-pink-400 text-white px-4 py-2 rounded-lg">Back</a>
        </div>
        <form>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block font-semibold">Admission ID: *</label>
                    <select class="w-full p-2 border rounded">
                        <option>Enter here...</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold">Bill Date: *</label>
                    <input type="text" class="w-full p-2 border rounded" placeholder="Enter here...">
                </div>
                <div>
                    <label class="block font-semibold">Patient: *</label>
                    <select class="w-full p-2 border rounded">
                        <option>Enter here...</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold">Patient Email: *</label>
                    <input type="email" class="w-full p-2 border rounded" placeholder="Enter here...">
                </div>
                <div>
                    <label class="block font-semibold">Patient DOB: *</label>
                    <input type="text" class="w-full p-2 border rounded" placeholder="Enter here...">
                </div>
                <div>
                    <label class="block font-semibold">Doctor:</label>
                    <input type="text" class="w-full p-2 border rounded" placeholder="Enter here...">
                </div>
                <div>
                    <label class="block font-semibold">Admission Date: *</label>
                    <input type="text" class="w-full p-2 border rounded" placeholder="Enter here...">
                </div>
                <div>
                    <label class="block font-semibold">Discharge Date: *</label>
                    <input type="text" class="w-full p-2 border rounded" placeholder="Enter here...">
                </div>
                <div>
                    <label class="block font-semibold">Package Name: *</label>
                    <input type="text" class="w-full p-2 border rounded" placeholder="Enter here...">
                </div>
                <div>
                    <label class="block font-semibold">Insurance Name: *</label>
                    <select class="w-full p-2 border rounded">
                        <option>Enter here...</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold">Total Days: *</label>
                    <input type="text" class="w-full p-2 border rounded" placeholder="Enter here...">
                </div>
                <div>
                    <label class="block font-semibold">Policy No: *</label>
                    <input type="text" class="w-full p-2 border rounded" placeholder="Enter here...">
                </div>
            </div>
            <div class="bg-gray-100 p-4 rounded-lg">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="p-2">#</th>
                            <th class="p-2">ITEM NAME*</th>
                            <th class="p-2">QTY*</th>
                            <th class="p-2">PRICE*</th>
                            <th class="p-2">AMOUNT*</th>
                            <th class="p-2">ACTION*</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="p-2">1</td>
                            <td class="p-2"><input type="text" class="w-full p-1 border rounded" placeholder="Item Name"></td>
                            <td class="p-2"><input type="number" class="w-full p-1 border rounded" placeholder="Qty"></td>
                            <td class="p-2"><input type="text" class="w-full p-1 border rounded" placeholder="Price"></td>
                            <td class="p-2">0.00</td>
                            <td class="p-2 text-center">🗑️</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-between items-center mt-4">
                <div class="text-lg font-semibold">Total Amount: AR$0</div>
                <div class="flex space-x-2">
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded">Save</button>
                    <button type="button" class="bg-gray-400 text-white px-4 py-2 rounded">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</body>


@endsection




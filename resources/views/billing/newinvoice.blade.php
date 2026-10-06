@extends('layouts.admin')

@section('title', 'Billing')

@section('content')




<body class="bg-gray-100 p-8">
    <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold">New Bill</h1>
            <button class="text-pink-500 border border-pink-500 px-4 py-1 rounded">Back</button>
        </div>
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block font-semibold">Patient: *</label>
                <select class="w-full p-2 border rounded-md"> <option>Enter here...</option> </select>
            </div>
            <div>
                <label class="block font-semibold">Invoice Date: *</label>
                <select class="w-full p-2 border rounded-md"> <option>Enter here...</option> </select>
            </div>
            <div>
                <label class="block font-semibold">Discount (%): *</label>
                <input type="text" class="w-full p-2 border rounded-md" placeholder="Enter here...">
            </div>
            <div>
                <label class="block font-semibold">Status: *</label>
                <input type="text" class="w-full p-2 border rounded-md" placeholder="Enter here...">
            </div>
        </div>
        
        <table class="w-full border-collapse border border-gray-300 mb-4">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">ACCOUNT*</th>
                    <th class="p-2 border">DESCRIPTION*</th>
                    <th class="p-2 border">QTY*</th>
                    <th class="p-2 border">PRICE*</th>
                    <th class="p-2 border">AMOUNT</th>
                    <th class="p-2 border">ACTION*</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td class="p-2 border">1</td>
                    <td class="p-2 border"><input type="text" class="w-full p-1 border rounded-md"></td>
                    <td class="p-2 border"><input type="text" class="w-full p-1 border rounded-md"></td>
                    <td class="p-2 border"><input type="number" class="w-full p-1 border rounded-md"></td>
                    <td class="p-2 border"><input type="text" class="w-full p-1 border rounded-md"></td>
                    <td class="p-2 border">0.00</td>
                     <td class="p-2 border">  <button class="fa fa-trash border p-1"></button></td>
              </tr>
                <tr class="text-center">
                    <td class="p-2 border">2</td>
                    <td class="p-2 border"><input type="text" class="w-full p-1 border rounded-md"></td>
                    <td class="p-2 border"><input type="text" class="w-full p-1 border rounded-md"></td>
                    <td class="p-2 border"><input type="number" class="w-full p-1 border rounded-md"></td>
                    <td class="p-2 border"><input type="text" class="w-full p-1 border rounded-md"></td>
                    <td class="p-2 border">0.00</td>
                    <td class="p-2 border">  <button class="fa fa-trash border p-1"></button></td>
                </tr>
            </tbody>
        </table>
        
        <div class="text-right mb-4">
            <p>SUB TOTAL: AR$ 0.00</p>
            <p>DISCOUNT: AR$ 0.00</p>
            <p class="font-bold">TOTAL AMOUNT: AR$ 0.00</p>
        </div>
        
        <div class="flex justify-end gap-2">
            <button class="bg-blue-600 text-white px-6 py-2 rounded-md">Save</button>
            <button class="bg-gray-400 text-white px-6 py-2 rounded-md">Cancel</button>
        </div>
    </div>
</body>

@endsection



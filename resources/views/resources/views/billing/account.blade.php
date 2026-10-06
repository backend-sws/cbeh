@extends('layouts.admin')

@section('title', 'Invoice Table')

@section('content')


<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Invoice</h1>
            <button class="flex items-center gap-2 text-pink-500 bg-pink-300 customborder border-pink-500 px-4 py-2 rounded" onclick="openModal()">
                 New Account
            </button>
        </div>
        
        <table class="w-full border-collapse border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 border">INVOICE ID</th>
                    <th class="p-2 border">TYPE</th>
                    <th class="p-2 border">STATUS</th>
                    <th class="p-2 border">ACTION</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center">
                    <td class="p-2 border text-pink-500">HASDFGADFY</td>
                    <td class="p-2 border"><span class="bg-red-200 text-red-600 px-2 py-1 rounded">Debit</span></td>
                    <td class="p-2 border"><input type="checkbox" class="toggle-checkbox"></td>
                     <td class="px-4 py-2">
                    <button class="fa fa-edit border p-1"></button>
                    <button class="ml-2 fa fa-trash border p-1"></button>
                </td>
                </tr>
                <tr class="text-center">
                    <td class="p-2 border text-pink-500">KXQLKJS</td>
                    <td class="p-2 border"><span class="bg-green-200 text-green-600 px-2 py-1 rounded">Credit</span></td>
                    <td class="p-2 border"><input type="checkbox" class="toggle-checkbox"></td>
                     <td class="px-4 py-2">
                    <button class="fa fa-edit border p-1"></button>
                    <button class="ml-2 fa fa-trash border p-1"></button>
                </td>
                </tr>
                <!-- Repeat rows dynamically -->
            </tbody>
        </table>
        
        <div class="flex justify-between items-center mt-4">
            <div class="flex gap-2">
                <button class="px-3 py-1 border rounded">&lt;</button>
                <button class="px-3 py-1 border rounded bg-pink-500 text-white">1</button>
                <button class="px-3 py-1 border rounded">2</button>
                <button class="px-3 py-1 border rounded">3</button>
                <button class="px-3 py-1 border rounded">4</button>
                <button class="px-3 py-1 border rounded">5</button>
                <button class="px-3 py-1 border rounded">&gt;</button>
            </div>
            <div class="flex items-center gap-2">
                <span>Show</span>
                <select class="border rounded p-1">
                    <option>16</option>
                    <option>32</option>
                </select>
                <span>Showing 1 to 10 of 3000</span>
                <select class="border rounded p-1">
                    <option>3000</option>
                </select>
                <span>Results</span>
            </div>
        </div>
    </div>
    
    <style>
        .toggle-checkbox {
            width: 40px;
            height: 20px;
            appearance: none;
            background: #ddd;
            border-radius: 10px;
            position: relative;
            outline: none;
            cursor: pointer;
        }
        .toggle-checkbox:checked {
            background: #6b46c1;
        }
        .toggle-checkbox:checked:after {
            left: 20px;
        }
        .toggle-checkbox:after {
            content: '';
            position: absolute;
            width: 18px;
            height: 18px;
            background: white;
            border-radius: 50%;
            top: 1px;
            left: 1px;
            transition: 0.3s;
        }
    </style>
</body>
  <!-- Modal -->
    <div id="employeeModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-semibold mb-4">New Employee Payroll</h2>
            <label class="block mb-2">Employee Name</label>
            <input type="text" class="w-full border p-2 rounded mb-4" placeholder="Enter Name">
            <label class="block mb-2">Net Salary</label>
            <input type="text" class="w-full border p-2 rounded mb-4" placeholder="Enter Salary">
            <div class="flex justify-end space-x-2">
                <button class="bg-gray-300 px-4 py-2 rounded" onclick="closeModal()">Cancel</button>
                <button class="bg-pink-500 text-white px-4 py-2 rounded">Save</button>
            </div>
        </div>
    </div>
    
    <script>
        function openModal() {
            document.getElementById('employeeModal').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('employeeModal').classList.add('hidden');
        }
    </script>

@endsection




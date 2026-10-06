@extends('layouts.admin')

@section('title', 'Edit Medicine')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mt-2 card bg-white p-4 rounded-lg md:flex-row justify-between items-start md:items-center mb-6">
        <div class="flex justify-between items-center w-full">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Edit Medicine</h2>
                <p class="text-sm text-gray-600 mt-1">Update medicine details and specifications</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.medicine.index') }}">
                    <button class="flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-sm font-medium transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Back
                    </button>
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <x-alert type="success" :message="session('success')" />
        <x-alert type="error" :message="session('error')" :errorsop="session('errorsop')" />
        <div class="msg-container"></div>

        <form id="medicine_edit_form" method="post" action="{{ route('admin.medicine.update', $medicine->id) }}" class="p-6 space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Medicine Name <span class="text-red-500">*</span></label>
                    <input type="text" name="medicine" value="{{ $medicine->medicine }}" 
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent" required />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                    <select name="category" 
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent" required>
                        <option disabled>Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $medicine->category == $category->id ? 'selected' : '' }}>
                                {{ $category->medicine_category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Brand <span class="text-red-500">*</span></label>
                    <select name="brand" 
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent" required>
                        <option disabled>Select a brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ $medicine->brand == $brand->id ? 'selected' : '' }}>
                                {{ $brand->brand }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Salt Composition</label>
                    <input type="text" name="salt_composition" value="{{ $medicine->salt_composition }}" 
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit <span class="text-red-500">*</span></label>
                    <select name="unit" 
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent" required>
                        <option disabled>Select a unit</option>
                        @php
                            $units = ['tablet' => 'Tablet', 'capsule' => 'Capsule', 'syrup' => 'Syrup', 'injection' => 'Injection', 'ointment' => 'Ointment', 'cream' => 'Cream', 'drop' => 'Drop', 'suspension' => 'Suspension', 'mg' => 'mg', 'ml' => 'ml', 'g' => 'g'];
                        @endphp
                        @foreach($units as $val => $label)
                            <option value="{{ $val }}" {{ strtolower($medicine->unit ?? '') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Strip Size (Pieces per Strip / Pack) <span class="text-red-500">*</span></label>
                    <input type="number" name="strip_quantity" id="strip_quantity" value="{{ $medicine->strip_quantity ?? 1 }}" min="1"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent" required />
                    <p class="text-xs text-gray-500 mt-1">Number of tablets/pieces in 1 strip or pack</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Buying Price (₹ / Strip)</label>
                    <input type="number" step="0.01" name="buying_price" id="buying_price" value="{{ $medicine->buying_price ?? 0 }}" min="0"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent" />
                    <p class="text-xs text-gray-500 mt-1" id="buy_per_piece_help">Master purchase price per strip</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Selling Price (₹ / Strip)</label>
                    <input type="number" step="0.01" name="selling_price" id="selling_price" value="{{ $medicine->selling_price ?? 0 }}" min="0"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent" />
                    <p class="text-xs text-gray-500 mt-1" id="sell_per_piece_help">Master selling price per strip</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Available Stock (in Strips)</label>
                    <input type="number" name="available_quantity" id="available_quantity" value="{{ $medicine->available_quantity ?? 0 }}" min="0"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent" />
                    <p class="text-xs text-gray-500 mt-1" id="calculated_pieces_help">Available stock in strips</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Stock (in Pieces)</label>
                    <input type="number" name="total_quantity" id="total_quantity" value="{{ $medicine->total_quantity ?? 0 }}" min="0"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent" />
                    <p class="text-xs text-gray-500 mt-1">Total pieces in stock</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Side Effects </label>
                    <textarea name="side_effects" rows="3" 
                        class="w-full border border-gray-300 rounded-md px-4 py-2 resize-none focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent">{{ $medicine->side_effects }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description </label>
                    <textarea name="description" rows="3" 
                        class="w-full border border-gray-300 rounded-md px-4 py-2 resize-none focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent">{{ $medicine->description }}</textarea>
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-4">
                <button type="submit" 
                    class="px-6 py-2 bg-main-color text-white rounded-md hover:bg-pink-700 transition-colors flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z"/>
                    </svg>
                    Update Medicine
                </button>
                <button type="reset" 
                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Reset
                </button>
            </div>
        </form>
    </div>
</div>

@push('js')
<script>
    function updateCalculations() {
        const stripSize = parseInt(document.getElementById('strip_quantity').value) || 1;
        const availableStrips = parseInt(document.getElementById('available_quantity').value) || 0;
        const totalPieces = parseInt(document.getElementById('total_quantity').value) || 0;

        document.getElementById('calculated_pieces_help').innerText = `Current stock: ${availableStrips} Strips (${availableStrips * stripSize} Pcs at ${stripSize} pcs/strip)`;

        const buyPrice = parseFloat(document.getElementById('buying_price').value) || 0;
        const sellPrice = parseFloat(document.getElementById('selling_price').value) || 0;

        if (stripSize > 0) {
            document.getElementById('buy_per_piece_help').innerText = `Master purchase: ₹${buyPrice.toFixed(2)}/strip (₹${(buyPrice / stripSize).toFixed(2)}/piece)`;
            document.getElementById('sell_per_piece_help').innerText = `Master selling: ₹${sellPrice.toFixed(2)}/strip (₹${(sellPrice / stripSize).toFixed(2)}/piece)`;
        }
    }

    document.getElementById('strip_quantity').addEventListener('input', function() {
        const stripSize = parseInt(this.value) || 1;
        const availableStrips = parseInt(document.getElementById('available_quantity').value) || 0;
        document.getElementById('total_quantity').value = availableStrips * stripSize;
        updateCalculations();
    });

    document.getElementById('available_quantity').addEventListener('input', function() {
        const stripSize = parseInt(document.getElementById('strip_quantity').value) || 1;
        const strips = parseInt(this.value) || 0;
        document.getElementById('total_quantity').value = strips * stripSize;
        updateCalculations();
    });

    document.getElementById('total_quantity').addEventListener('input', function() {
        const stripSize = parseInt(document.getElementById('strip_quantity').value) || 1;
        const pieces = parseInt(this.value) || 0;
        if (stripSize > 0) {
            document.getElementById('available_quantity').value = Math.floor(pieces / stripSize);
        }
        updateCalculations();
    });

    document.getElementById('buying_price').addEventListener('input', updateCalculations);
    document.getElementById('selling_price').addEventListener('input', updateCalculations);
    document.addEventListener('DOMContentLoaded', updateCalculations);
</script>
@endpush

@endsection
@extends('layouts.admin')

@section('title', 'Add Medicine')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mt-2 card bg-white p-4 rounded-lg md:flex-row justify-between items-start md:items-center mb-6">
        <div class="flex justify-between items-center w-full">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Add New Medicine</h2>
                <p class="text-sm text-gray-600 mt-1">Enter medicine details and specifications</p>
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

        <form id="medicine_add_form" method="post" action="{{route('admin.medicine.save')}}" class="p-6 space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Medicine Name <span class="text-red-500">*</span></label>
                    <input type="text" name="medicine" placeholder="Enter medicine name" 
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent" required />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                    <select name="category" 
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent" required>
                        <option selected disabled value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->medicine_category }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Brand <span class="text-red-500">*</span></label>
                    <select name="brand" 
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent" required>
                        <option selected disabled value="">Select a brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->brand }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Salt Composition </label>
                    <input type="text" name="salt_composition" placeholder="Enter salt composition" 
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit <span class="text-red-500">*</span></label>
                    <select name="unit" 
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent" required>
                        <option selected disabled value="">Select a unit</option>
                        <option value="tablet">Tablet</option>
                        <option value="capsule">Capsule</option>
                        <option value="syrup">Syrup</option>
                        <option value="injection">Injection</option>
                        <option value="ointment">Ointment</option>
                        <option value="cream">Cream</option>
                        <option value="drop">Drop</option>
                        <option value="suspension">Suspension</option>
                        <option value="mg">mg</option>
                        <option value="ml">ml</option>
                        <option value="g">g</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Strip Size (Pieces per Strip / Pack) <span class="text-red-500">*</span></label>
                    <input type="number" name="strip_quantity" id="strip_quantity" placeholder="e.g. 10" min="1" value="10"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent" required />
                    <p class="text-xs text-gray-500 mt-1">Number of tablets/pieces in 1 strip or pack (e.g. 10)</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Buying Price (₹ / Strip)</label>
                    <input type="number" step="0.01" name="buying_price" id="buying_price" placeholder="0.00" min="0" value="0.00"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent" />
                    <p class="text-xs text-gray-500 mt-1" id="buy_per_piece_help">Master purchase price per strip</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Selling Price (₹ / Strip)</label>
                    <input type="number" step="0.01" name="selling_price" id="selling_price" placeholder="0.00" min="0" value="0.00"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent" />
                    <p class="text-xs text-gray-500 mt-1" id="sell_per_piece_help">Master sale price per strip (auto-calculates piece price)</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Opening Stock (in Strips)</label>
                    <input type="number" name="opening_stock_strips" id="opening_stock_strips" placeholder="0" min="0" value="0"
                        class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent" />
                    <p class="text-xs text-gray-500 mt-1" id="calculated_pieces_help">Initial available strips (optional)</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Pieces (Stock)</label>
                    <input type="number" id="total_pieces_preview" placeholder="0" min="0" value="0" readonly
                        class="w-full bg-gray-100 border border-gray-300 rounded-md px-4 py-2 focus:outline-none text-gray-700 font-semibold cursor-not-allowed" />
                    <p class="text-xs text-gray-500 mt-1">Calculated as: Strips × Strip Size</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Side Effects </label>
                    <textarea name="side_effects" rows="3" placeholder="Enter side effects" 
                        class="w-full border border-gray-300 rounded-md px-4 py-2 resize-none focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent"></textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description </label>
                    <textarea name="description" rows="3" placeholder="Enter description" 
                        class="w-full border border-gray-300 rounded-md px-4 py-2 resize-none focus:outline-none focus:ring-2 focus:ring-main-color focus:border-transparent"></textarea>
                </div>
            </div>

            <div class="flex justify-end space-x-4 pt-4">
                <button type="submit" 
                    class="px-6 py-2 bg-main-color text-white rounded-md hover:bg-pink-700 transition-colors flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Save Medicine
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
        const openingStrips = parseInt(document.getElementById('opening_stock_strips').value) || 0;
        const totalPieces = stripSize * openingStrips;
        
        document.getElementById('total_pieces_preview').value = totalPieces;
        document.getElementById('calculated_pieces_help').innerText = `Total pieces: ${totalPieces} Pcs (${openingStrips} Strips × ${stripSize} Pcs/Strip)`;

        const buyPrice = parseFloat(document.getElementById('buying_price').value) || 0;
        const sellPrice = parseFloat(document.getElementById('selling_price').value) || 0;

        if (stripSize > 0) {
            document.getElementById('buy_per_piece_help').innerText = `Master purchase: ₹${buyPrice.toFixed(2)}/strip (₹${(buyPrice / stripSize).toFixed(2)}/piece)`;
            document.getElementById('sell_per_piece_help').innerText = `Master selling: ₹${sellPrice.toFixed(2)}/strip (₹${(sellPrice / stripSize).toFixed(2)}/piece)`;
        }
    }

    document.getElementById('strip_quantity').addEventListener('input', updateCalculations);
    document.getElementById('opening_stock_strips').addEventListener('input', updateCalculations);
    document.getElementById('buying_price').addEventListener('input', updateCalculations);
    document.getElementById('selling_price').addEventListener('input', updateCalculations);
    document.addEventListener('DOMContentLoaded', updateCalculations);
</script>
@endpush

@endsection
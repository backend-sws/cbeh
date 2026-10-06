@extends('layouts.admin')
@section('title', 'Expired Medicine Batches')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4">
    
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-500"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">
                    Showing expired <strong>Batches</strong> available in stock.
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Medicine</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Batch No</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Expiry Date</th>
                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Batch Stock</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($expired_batches as $batch)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{-- Relation se naam nikala --}}
                        {{ $batch->medicine->medicine ?? 'Unknown' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $batch->lot_no ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            {{ \Carbon\Carbon::parse($batch->expiry_date)->format('d M, Y') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold">
                        {{ $batch->quantity }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        {{-- ID ab Batch ki ID hogi --}}
                        <button onclick="openDumpModal({{ $batch->id }}, '{{ $batch->medicine->medicine ?? 'Item' }} ({{ $batch->batch_no }})', {{ $batch->quantity }})"
                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-xs font-bold shadow transition-colors">
                            <i class="fas fa-trash-alt mr-1"></i> Dump
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                        <i class="fas fa-check-circle text-green-500 text-3xl mb-2"></i>
                        <p>No expired batches found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="dumpModal" class="fixed inset-0 bg-black bg-opacity-60 hidden z-50 flex justify-center items-center backdrop-blur-sm">
    <div class="bg-white rounded-lg shadow-xl w-96 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">Confirm Batch Dump</h3>
        <p class="text-sm text-gray-600 mb-4">Removing: <span id="modal_med_name" class="font-bold text-red-600"></span></p>

        <form id="dumpForm" onsubmit="submitDump(event)">
            @csrf
            
            {{-- NAME CHANGE: medicine_id ki jagah purchased_medicine_id --}}
            <input type="hidden" name="purchased_medicine_id" id="modal_batch_id">
            
            <div class="mb-3">
                <label class="block text-xs font-bold text-gray-700">Quantity (Max: <span id="modal_max_qty"></span>)</label>
                <input type="number" name="quantity" id="modal_qty" class="w-full border rounded p-2 text-sm" required min="1">
            </div>

            <div class="mb-3">
                <label class="block text-xs font-bold text-gray-700">Reason</label>
                <select name="reason" class="w-full border rounded p-2 text-sm">
                    <option value="Expired">Expired</option>
                    <option value="Damaged">Damaged</option>
                    <option value="Lost">Lost</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-700">Note</label>
                <textarea name="note" class="w-full border rounded p-2 text-sm" rows="2"></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeDumpModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded text-sm">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded text-sm font-bold hover:bg-red-700">Confirm</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    function openDumpModal(id, name, qty) {
        $('#modal_batch_id').val(id); // Batch ID set karo
        $('#modal_med_name').text(name);
        $('#modal_max_qty').text(qty);
        $('#modal_qty').attr('max', qty).val(qty); 
        $('#dumpModal').removeClass('hidden');
    }

    function closeDumpModal() {
        $('#dumpModal').addClass('hidden');
    }

    function submitDump(e) {
        e.preventDefault();
        if(!confirm("Are you sure? This will remove items from stock.")) return;

        let formData = new FormData(document.getElementById('dumpForm'));

        $.ajax({
            url: "{{ route('admin.medicine.dump.save') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                if(res.status === 1) {
                    alert(res.message);
                    window.location.reload();
                } else {
                    alert(res.message);
                }
            },
            error: function() {
                alert("Something went wrong");
            }
        });
    }
</script>
@endpush
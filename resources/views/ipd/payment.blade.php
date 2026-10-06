<!-- Add IPD Payment Modal -->
<div id="IpdPaymentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center overflow-y-auto">
    <div class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-lg mx-auto my-10 relative overflow-y-auto max-h-[90vh]">
        <button id="closeIpdPaymentModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">
            &times;
        </button>
        <form action="{{ route('admin.Payment.add') }}" method="POST" enctype="multipart/form-data" id="ipdPaymentForm" onsubmit="formSubmit2(event, this, false, 'ipdPaymentForm')">
            @csrf
            <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center border-b pb-2">Add IPD Payment</h2>

            <input type="hidden" id="patient_id" name="patient_id" value="{{ $ipd_patient->patient->id }}">

            <div class="space-y-4">
                <div>
                    <label for="ipd_no" class="block text-gray-700 text-sm font-bold mb-1">IPD No:<span class="text-red-500">*</span></label>
                    <input type="text" id="ipd_no" name="ipd_no" value="{{ $ipd_patient->ipd_no }}" readonly class="shadow border rounded w-full py-2 px-3 text-gray-700 bg-gray-100">
                </div>

                <div>
                    <label for="payment_date" class="block text-gray-700 text-sm font-bold mb-1">Payment Date:<span class="text-red-500">*</span></label>
                    <input type="date" id="payment_date" value="{{ date('Y-m-d') }}" name="payment_date" class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:ring focus:ring-purple-200">
                </div>

                <div>
                    <label for="amount" class="block text-gray-700 text-sm font-bold mb-1">Amount:<span class="text-red-500">*</span></label>
                    <input type="number" id="amount" name="amount" step="0.01" class="shadow border rounded w-full py-2 px-3 text-gray-700" placeholder="Enter Amount">
                </div>

                <div>
                    <label for="payment_mode_id" class="block text-gray-700 text-sm font-bold mb-1">Payment Mode:<span class="text-red-500">*</span></label>
                    <select id="payment_mode_id" name="payment_mode_id" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                        <option value="">Select Payment Mode</option>
                    </select>
                </div>

                <div>
                    <label for="document" class="block text-gray-700 text-sm font-bold mb-1">Document (optional):</label>
                    <input type="file" id="document" name="document" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                </div>

                <div>
                    <label for="notes" class="block text-gray-700 text-sm font-bold mb-1">Notes:</label>
                    <textarea id="notes" name="notes" class="shadow border rounded w-full py-2 px-3 text-gray-700" rows="3" placeholder="Enter any notes..."></textarea>
                </div>
            </div>

            <div class="flex justify-end mt-6 border-t pt-4">
                <button class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded-md mr-2 transition">Save</button>
                <button type="button" id="closeIpdModalBtnBottom" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded-md transition">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="eIpdPaymentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center overflow-y-auto">
    <div class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-lg mx-auto my-10 relative overflow-y-auto max-h-[90vh]">
        <button id="ecloseIpdPaymentModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">
            &times;
        </button>
        <form method="POST" enctype="multipart/form-data" id="ipdPaymentForm" onsubmit="formSubmit2(event, this, false, 'ipdPaymentForm')">
            @csrf
            @method('PUT')
            <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center border-b pb-2">Edit IPD Payment</h2>

            <input type="hidden" id="epatient_id" name="patient_id">

            <div class="space-y-4">
                <div>
                    <label for="eipd_no" class="block text-gray-700 text-sm font-bold mb-1">IPD No:<span class="text-red-500">*</span></label>
                    <input type="text" id="eipd_no" name="ipd_no" readonly class="shadow border rounded w-full py-2 px-3 text-gray-700 bg-gray-100">
                </div>

                <div>
                    <label for="epayment_date" class="block text-gray-700 text-sm font-bold mb-1">Payment Date:<span class="text-red-500">*</span></label>
                    <input type="date" id="epayment_date" name="payment_date" class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:ring focus:ring-purple-200">
                </div>

                <div>
                    <label for="eamount" class="block text-gray-700 text-sm font-bold mb-1">Amount:<span class="text-red-500">*</span></label>
                    <input type="number" id="eamount" name="amount" step="0.01" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                </div>

                <div>
                    <label for="epayment_mode_id" class="block text-gray-700 text-sm font-bold mb-1">Payment Mode:<span class="text-red-500">*</span></label>
                    <select id="epayment_mode_id" name="payment_mode_id" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                        <option value="">Select Payment Mode</option>
                    </select>
                </div>

                <div>
                    <label for="edocument" class="block text-gray-700 text-sm font-bold mb-1">Document (optional):</label>
                    <input type="file" id="edocument" name="document" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                    <div id="ecurrentDocument" class="mt-2 text-sm text-blue-600"></div>
                </div>

                <div>
                    <label for="enotes" class="block text-gray-700 text-sm font-bold mb-1">Notes:</label>
                    <textarea id="enotes" name="notes" class="shadow border rounded w-full py-2 px-3 text-gray-700" rows="3"></textarea>
                </div>
            </div>

            <div class="flex justify-end mt-6 border-t pt-4">
                <button class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded-md mr-2 transition">Update</button>
                <button type="button" id="ecloseIpdModalBtnBottom" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded-md transition">Cancel</button>
            </div>
        </form>
    </div>
</div>


<script>
function fetchpaymentbyPatient(id) {
    console.log("ipd id", id);
    
    var urlcharge = "{{ route('admin.Payment.list', ':id') }}";
    urlcharge = urlcharge.replace(':id', id);

    fetch(urlcharge)
        .then(response => response.json())
        .then(data => {
            console.log("Payment data", data);

            const ipdPayments = data;
            const PaymentTableContainer = document.getElementById('PaymentTableContainer');

            if (ipdPayments.length > 0) {
                let tableHtml = `
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Date</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Amount</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Payment Mode</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Document</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Note
</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                `;

                // Get delete route base (without ID)
                const deleteRouteTemplate = "{{ route('admin.Payment.delete', ':id') }}";
                const userRole = "{{ auth()->user()->role ?? '' }}";

              ipdPayments.forEach(payment => {
            const deleteUrl = deleteRouteTemplate.replace(':id', payment.id);
            const paymentMode = payment.payment_mode?.name || 'N/A';
            const formattedDate = formatDate(payment.payment_date);

            const deleteButton = userRole === 'Admin' ? `
                <form action="${deleteUrl}" method="POST" style="display:inline;" id="deletePaymentForm" onsubmit="formSubmit2(event, this, false, 'deletePaymentForm')"> 
                @method('DELETE')
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this payment?');">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            ` : '';

            tableHtml += `
    <tr>
        <td class="px-4 py-2 text-sm text-gray-700">${formattedDate}</td>
        <td class="px-4 py-2 text-sm text-gray-700">${payment.amount}</td>
        <td class="px-4 py-2 text-sm text-gray-700">${paymentMode}</td>
         <td class="px-4 py-2 text-sm text-gray-700">
            ${payment.document ? `
                <a href="{{ asset('doc/${payment.document}') }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                    
                
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/PDF_file_icon.svg/1200px-PDF_file_icon.svg.png"  width="50" height="50" /> </a>
            ` : 'N/A'}
        </td>
        <td class="px-4 py-2 text-sm text-gray-700">${payment.notes || 'N/A'}</td>
        <td class="px-4 py-2 text-sm">
            ${deleteButton}
        
            <a href="{{ route('admin.ipd.payment.print', '') }}/${payment.id}" target="_blank" class="ml-2 text-green-600 hover:text-green-800">
                <i class="fas fa-print"></i>
            </a>
        </td>
    </tr>
`;
        });


                tableHtml += `
                            </tbody>
                        </table>
                    </div>
                `;
                PaymentTableContainer.innerHTML = tableHtml;

            } else {
        tableHtml = `
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Payment Date</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Amount</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Payment Mode</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Notes</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-500">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="5" class="px-4 py-2 text-sm text-gray-700 text-center">No payments found for this patient.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
    }
        })

      
        .catch(error => console.error('Error fetching charges data:', error));
}

// Optional: utility function to format date
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}


</script>
 <script>
document.addEventListener('DOMContentLoaded', function () {
    fetch("{{ route('admin.Payment.Mode.list') }}")
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const paymentModeSelect = document.getElementById('payment_mode_id');
            const editPaymentModeSelect = document.getElementById('epayment_mode_id');
            
            // Clear existing options except the first one
            while (paymentModeSelect.options.length > 1) {
                paymentModeSelect.remove(1);
            }
            while (editPaymentModeSelect.options.length > 1) {
                editPaymentModeSelect.remove(1);
            }
            
            // Add new options to both selects
            data.forEach(function (item) {
                // For create form
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                paymentModeSelect.appendChild(option);
                
                // For edit form (clone the option)
                const editOption = option.cloneNode(true);
                editPaymentModeSelect.appendChild(editOption);
            });
        })
        .catch(error => {
            console.error('Error fetching payment modes:', error);
            // You might want to show an error message to the user here
        });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.body.addEventListener('click', function (e) {
        if (e.target.closest('.openEditPaymentBtn')) {
            const button = e.target.closest('.openEditPaymentBtn');
            const payment = JSON.parse(button.getAttribute('data-payment'));
            
            // Set form action for update
            document.getElementById('ipdPaymentForm').action = '{{ route("admin.Payment.update", "") }}/' + payment.id;
            
            // Set modal fields
            document.getElementById('epatient_id').value = payment.patient_id;
            document.getElementById('eipd_no').value = payment.ipd_no;
            document.getElementById('epayment_date').value = payment.payment_date.split(' ')[0]; // Format date if needed
            document.getElementById('eamount').value = payment.amount;
            document.getElementById('epayment_mode_id').value = payment.payment_mode_id;
            document.getElementById('enotes').value = payment.notes || '';
            
            // Show current document filename if exists
            if (payment.document) {
                // You might want to show the current document name/link here
            }

            // Show modal
            document.getElementById('eIpdPaymentModal').classList.remove('hidden');
            document.getElementById('eIpdPaymentModal').classList.add('flex');
        }
    });

    // Close buttons
    const eClose1 = document.getElementById('ecloseIpdPaymentModal');
    if (eClose1) {
        eClose1.addEventListener('click', () => {
            const eModal = document.getElementById('eIpdPaymentModal');
            if (eModal) eModal.classList.add('hidden');
        });
    }

    const eClose2 = document.getElementById('ecloseIpdModalBtnBottom');
    if (eClose2) {
        eClose2.addEventListener('click', () => {
            const eModal = document.getElementById('eIpdPaymentModal');
            if (eModal) eModal.classList.add('hidden');
        });
    }
});
</script>
<!-- Add OPD Payment Modal -->
<div id="OpdPaymentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center overflow-y-auto">
    <div class="bg-white rounded-lg shadow-2xl p-6 w-full max-w-lg mx-auto my-10 relative overflow-y-auto max-h-[90vh]">
        <button id="closeOpdPaymentModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">
            &times;
        </button>
        <form action="{{ route('admin.OpdPayment.add') }}" method="POST" enctype="multipart/form-data" id="opdPaymentForm" onsubmit="formSubmit2(event, this, false, 'opdPaymentForm')">
            @csrf
            <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center border-b pb-2">Add OPD Payment / Receipt</h2>

            <input type="hidden" id="opd_patient_id_payment" name="patient_id" value="{{ $opd_patient->patient_id ?? ($opd_patient->patient->id ?? '') }}">

            <div class="space-y-4">
                <div>
                    <label for="pay_opd_no" class="block text-gray-700 text-sm font-bold mb-1">OPD No:<span class="text-red-500">*</span></label>
                    <input type="text" id="pay_opd_no" name="opd_no" value="{{ $opd_patient->opd_no }}" readonly class="shadow border rounded w-full py-2 px-3 text-gray-700 bg-gray-100">
                </div>

                <div>
                    <label for="payment_date" class="block text-gray-700 text-sm font-bold mb-1">Payment Date:<span class="text-red-500">*</span></label>
                    <input type="date" id="payment_date" value="{{ date('Y-m-d') }}" name="payment_date" class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:ring focus:ring-purple-200">
                </div>

                <div>
                    <label for="amount" class="block text-gray-700 text-sm font-bold mb-1">Amount:<span class="text-red-500">*</span></label>
                    <input type="number" id="amount" name="amount" step="0.01" class="shadow border rounded w-full py-2 px-3 text-gray-700" placeholder="Enter Amount" required>
                </div>

                <div>
                    <label for="payment_mode_id" class="block text-gray-700 text-sm font-bold mb-1">Payment Mode:<span class="text-red-500">*</span></label>
                    <select id="payment_mode_id" name="payment_mode_id" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                        <option value="">Select Payment Mode</option>
                    </select>
                </div>

                <div>
                    <label for="document" class="block text-gray-700 text-sm font-bold mb-1">Document (optional):</label>
                    <input type="file" id="document" name="document" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                </div>

                <div>
                    <label for="notes" class="block text-gray-700 text-sm font-bold mb-1">Notes:</label>
                    <textarea id="notes" name="notes" class="shadow border rounded w-full py-2 px-3 text-gray-700" rows="3" placeholder="Enter any notes or transaction reference..."></textarea>
                </div>
            </div>

            <div class="flex justify-end mt-6 border-t pt-4">
                <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded-md mr-2 transition">Save</button>
                <button type="button" id="closeOpdModalBtnBottom" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded-md transition">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function fetchpaymentbyPatient(id) {
    var urlcharge = "{{ route('admin.OpdPayment.list', ':id') }}".replace(':id', id);

    fetch(urlcharge)
        .then(response => response.json())
        .then(data => {
            const PaymentTableContainer = document.getElementById('PaymentTableContainer');
            if (!PaymentTableContainer) return;

            if (Array.isArray(data) && data.length > 0) {
                let tableHtml = `
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Date</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Amount</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Payment Mode</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Document</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Note</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-700">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                `;

                const deleteRouteTemplate = "{{ route('admin.OpdPayment.delete', ':id') }}";
                const userRole = "{{ auth()->user()->role ?? 'Admin' }}";

                data.forEach(payment => {
                    const deleteUrl = deleteRouteTemplate.replace(':id', payment.id);
                    const paymentMode = payment.payment_mode?.name || 'N/A';
                    const formattedDate = formatPaymentDate(payment.payment_date);

                    const deleteButton = (payment.id !== 0 && (userRole === 'Admin' || userRole === 'Super Admin' || true)) ? `
                        <form action="${deleteUrl}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this payment?');"> 
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    ` : '<span class="text-xs text-gray-400">Fixed</span>';

                    tableHtml += `
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-700">${formattedDate}</td>
                            <td class="px-4 py-2 text-sm text-gray-700 font-semibold">&#8377;${parseFloat(payment.amount).toFixed(2)}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">${paymentMode}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">
                                ${payment.document ? `
                                    <a href="{{ asset('doc') }}/${payment.document}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/PDF_file_icon.svg/1200px-PDF_file_icon.svg.png" width="30" height="30" alt="doc" />
                                    </a>
                                ` : 'N/A'}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-700">${payment.notes || 'N/A'}</td>
                            <td class="px-4 py-2 text-sm">
                                ${deleteButton}
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
                PaymentTableContainer.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-hand-holding-usd text-4xl text-gray-300 mb-4 block"></i>
                        <p>No payments received yet for this patient.</p>
                    </div>
                `;
            }
        })
        .catch(error => console.error('Error fetching payments data:', error));
}

function formatPaymentDate(dateString) {
    if(!dateString) return '-';
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}

document.addEventListener('DOMContentLoaded', function () {
    fetch("{{ route('admin.Payment.Mode.list') }}")
        .then(response => response.json())
        .then(data => {
            const paymentModeSelect = document.getElementById('payment_mode_id');
            if (!paymentModeSelect) return;
            
            while (paymentModeSelect.options.length > 1) {
                paymentModeSelect.remove(1);
            }
            
            data.forEach(function (item) {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                paymentModeSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching payment modes:', error));
});
</script>

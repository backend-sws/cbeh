
    
        <!-- Medicine Table -->
        <div class="mb-4">
            
            <table class="w-full text-left border text-sm">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="p-2 border">Date</th>
                        <th class="p-2 border">Charge Type</th>
                        <th class="p-2 border">Code</th>
                        <th class="p-2 border">Standard Charge</th>
                        <th class="p-2 border">Applied Charge</th>
                        <th class="p-2 border">Action</th>
                    </tr>
                </thead>
                <tbody id="medicineTableBody"></tbody>
            </table>
        </div>

       

<script>
    function predictiondata(id) {
    let predetailapiurl = "{{ route('admin.billing.get_billing_charge', ':id') }}";
    predetailapiurl = predetailapiurl.replace(':id', id);

    fetch(predetailapiurl)
        .then(response => response.json())
        .then(data => {
            
            const medicineTable = document.getElementById('medicineTableBody');
            medicineTable.innerHTML = '';
            (data.medicines || []).forEach(med => {
                let tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="border p-2">${med.medicine.categoryRelation ?? 'N/A'}</td>
                    <td class="border p-2">${med.medicine.medicine ?? 'N/A'}</td>
                    <td class="border p-2">${med.dosage ?? 'N/A'}(${med.time ?? 'N/A'})</td>
                    <td class="border p-2">${med.doseInterval ?? 'N/A'}</td>
                    <td class="border p-2">${med.instructions ?? 'N/A'}</td>
                `;
                medicineTable.appendChild(tr);
            });
        })
        .catch(error => console.error('Error fetching prescription data:', error));
}

    

    function openModalprecription(id) {
        predictiondata(id);
        const modal = document.getElementById('newBedModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function printPrescription() {
        const originalBody = document.body.innerHTML;
        const printContent = document.getElementById('printArea').innerHTML;
        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalBody;
        window.location.reload(); // reload page to reset state
    }

    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('newBedModal');
        const closeBtn = document.getElementById('closeModalBtn');

        if (closeBtn && modal) {
            closeBtn.addEventListener('click', function () {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });
        }

        window.onclick = function (event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        };
    });
</script>

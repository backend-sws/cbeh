<!-- Add Operation Category Modal -->
<div id="AddCategoryModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 max-h-[90vh] flex flex-col">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Add Operation Category</h2>
            <button onclick="closeModal('AddCategoryModal')" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        <div class="p-4 overflow-y-auto">
            <form action="{{ route('admin.operation.category.add') }}" method="POST" onsubmit="PostWithoutReload(event, this, true, 'categoryForm','loadOperationCategories')">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
                    <input type="text" name="name" class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="text-right">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Save</button>
                </div>
            </form>
            <div id="operation_category_list" class="mt-4 max-h-[300px] overflow-y-auto border border-gray-200 rounded-md p-2"></div>
        </div>
    </div>
</div>

<!-- Add Operation Name Modal -->
<div id="AddOperationNameModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 max-h-[90vh] flex flex-col">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Add Operation Name</h2>
            <button onclick="closeModal('AddOperationNameModal')" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        <div class="p-4 overflow-y-auto">
            <form action="{{ route('admin.operation.name.add') }}" method="POST" id="operationNameForm" onsubmit="PostWithoutReload(event, this, true, 'operationNameForm', 'loadOperationName')">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Category</label>
                    <select name="category_id" class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @foreach(App\Models\OperationCategory::all() as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Operation Name</label>
                    <input type="text" name="name" class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="text-right">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Save</button>
                </div>
            </form>
            <div id="operation_name_list" class="mt-4 max-h-[300px] overflow-y-auto border border-gray-200 rounded-md p-2"></div>
        </div>
    </div>
</div>

<!-- Main Operation Modal -->
<div id="OperationModel" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Add New Operation</h2>
            <button id="closeoperationModel" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        <div class="p-4 overflow-y-auto">
            <form action="{{ route('admin.operation.opnemeadd') }}" method="POST" id="addopform" onsubmit="formSubmit2(event, this, false, 'addopform')">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $ipd_patient->patient_id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="operation_date" class="block text-sm font-medium text-gray-700 mb-1">Operation Date</label>
                        <input type="date" name="operation_date" id="operation_date" class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="flex gap-2 items-end">
                        <div class="flex-1">
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category_id" id="category_id" class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Category</option>
                                @foreach(App\Models\OperationCategory::all() as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" onclick="openModal('AddCategoryModal')" class="bg-green-500 text-white px-3 py-2 rounded-md hover:bg-green-600 h-[42px]">+</button>
                    </div>

                    <div class="flex gap-2 items-end">
                        <div class="flex-1">
                            <label for="operation_id" class="block text-sm font-medium text-gray-700 mb-1">Operation Name</label>
                            <select name="operation_id" id="operation_id" class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Operation Name</option>
                            </select>
                        </div>
                        <button type="button" onclick="openModal('AddOperationNameModal')" class="bg-green-500 text-white px-3 py-2 rounded-md hover:bg-green-600 h-[42px]">+</button>
                    </div>

                    <div>
                        <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-1">Doctor</label>
                        <select name="doctor_id" id="doctor_id" class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select</option>
                            @foreach(App\Models\User::role('Doctor')->select('id','name','email')->get() as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="assistant_consultant1" class="block text-sm font-medium text-gray-700 mb-1">Assistant Consultant 1</label>
                        <input type="text" name="assistant_consultant1" id="assistant_consultant1" class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="assistant_consultant2" class="block text-sm font-medium text-gray-700 mb-1">Assistant Consultant 2</label>
                        <input type="text" name="assistant_consultant2" id="assistant_consultant2" class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="anesthetist" class="block text-sm font-medium text-gray-700 mb-1">Anesthetist</label>
                        <input type="text" name="anesthetist" id="anesthetist" class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="anesthesia_type" class="block text-sm font-medium text-gray-700 mb-1">Anesthesia Type</label>
                        <input type="text" name="anesthesia_type" id="anesthesia_type" class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="ot_technician" class="block text-sm font-medium text-gray-700 mb-1">OT Technician</label>
                        <input type="text" name="ot_technician" id="ot_technician" class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="ot_assistant" class="block text-sm font-medium text-gray-700 mb-1">OT Assistant</label>
                        <input type="text" name="ot_assistant" id="ot_assistant" class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="md:col-span-2">
                        <label for="remark" class="block text-sm font-medium text-gray-700 mb-1">Remark</label>
                        <textarea name="remark" id="remark" rows="3" class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label for="result" class="block text-sm font-medium text-gray-700 mb-1">Result</label>
                        <textarea name="result" id="result" rows="3" class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>

                <div class="flex justify-end mt-6 gap-2">
                    <button type="button" id="closeModalBtnBottom" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-2 px-4 rounded-md">Cancel</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('OperationModel');
    const openBtn = document.getElementById('tab-operations');
    const operationsContainer = document.getElementById('content-operations');

    // Fetch operations data when tab is clicked
    if (openBtn && operationsContainer) {
        openBtn.addEventListener('click', function () {
            const id = "{{ $ipd_patient->patient_id }}";

            let urlcharge = "{{ route('admin.operation.opnamelist', ':id') }}";
            urlcharge = urlcharge.replace(':id', id);

            fetch(urlcharge)
                .then(response => response.json())
                .then(data => {
                    console.log("Operation data", data);

                    let tableHtml = `
                        <div class="overflow-x-auto">
                            <div class="flex justify-between items-center mb-4">
                                <button type="button" id="openModaloperation" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded float-right">
                                    Add New Operation
                                </button>
                            </div>
                            <table class="w-full border-collapse">
                                <thead>
                                    <tr class="bg-gray-200 text-gray-700 uppercase text-sm">
                                        <th class="p-3 text-left">Reference ID</th>
                                        <th class="p-3 text-left">Operation Date</th>
                                        <th class="p-3 text-left">Operation Name</th>
                                        <th class="p-3 text-left">Operation Category Name</th>
                                        <th class="p-3 text-left">OT Technician</th>
                                        <th class="p-3 text-left">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm">`;

                    const deleteRouteTemplate = "{{ route('admin.operation.opnemedelete', ':id') }}";

                    if (data && data.data && Array.isArray(data.data)) {
                        data.data.forEach(operation => {
                            const deleteUrl = deleteRouteTemplate.replace(':id', operation.id);

                            tableHtml += `
                                <tr class="border-b hover:bg-gray-100">
                                    <td class="p-3">${operation.id}</td>
                                    <td class="p-3">${operation.operation_date || ''}</td>
                                    <td class="p-3">${operation.operation_name ? operation.operation_name.name : ''}</td>
                                    <td class="p-3">${operation.operation_category ? operation.operation_category.name : 'N/A'}</td>
                                    <td class="p-3">${operation.ot_technician || ''}</td>
                                    <td class="p-3">
                                     <button type="button" class="ml-2 text-blue-500 hover:text-blue-700" onclick='openEditOperation(${JSON.stringify(operation)})'>
                <i class="fas fa-edit"></i>
            </button>
                                     <form action="${deleteUrl}" method="POST" style="display:inline;" id="addoperationdelete" onsubmit="formSubmit2(event, this, false, 'addoperationdelete')" >
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-danger" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                                <i class="fas fa-trash" style="color:red;"></i>
                                            </button>
                                        </form>
                                        
                                    </td>
                                </tr>`;
                        });
                    }

                    tableHtml += `
                                </tbody>
                            </table>
                        </div>`;

                    operationsContainer.innerHTML = tableHtml;

                    // Now bind the click event for the new "Add New Operation" button
                    const addOpBtn = document.getElementById('openModaloperation');
                    if (addOpBtn && modal) {
                        addOpBtn.addEventListener('click', function () {
                            modal.classList.remove('hidden');
                            modal.classList.add('flex', 'overflow-y-auto');
                            modal.scrollTop = 0;
                        });
                    }
                })
                .catch(error => {
                    console.error("Error fetching operations:", error);
                    operationsContainer.innerHTML = `<p class="text-red-600">Failed to load operation data.</p>`;
                });
        });
    }

    // Close buttons
    const closeOp1 = document.getElementById('closeoperationModel');
    if (closeOp1) closeOp1.addEventListener('click', closeModal);
    const closeOp2 = document.getElementById('closeModalBtnBottom');
    if (closeOp2) closeOp2.addEventListener('click', closeModal);

    function closeModal() {
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex', 'overflow-y-auto');
        }
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const categorySelect = document.getElementById('category_id');
    const operationSelect = document.getElementById('operation_id');
    if (categorySelect && operationSelect) {
        loadOperationCategories();

        // Populate operation names when category changes
        categorySelect.addEventListener('change', function () {
        const categoryId = this.value;
        operationSelect.innerHTML = '<option value="">Loading...</option>';
        const RouteTemplate = "{{ route('admin.operation.get-operations-by-category', ':id') }}";
        const catopUrl = RouteTemplate.replace(':id', categoryId);
        if (catopUrl && categoryId) {
            fetch(catopUrl)
                .then(response => response.json())
                .then(data => {
                    operationSelect.innerHTML = '<option value="">Select Operation Name</option>';
                    data.forEach(operation => {
                        const option = document.createElement('option');
                        option.value = operation.id;
                        option.textContent = operation.name;
                        operationSelect.appendChild(option);
                    });
                });
        } else {
            operationSelect.innerHTML = '<option value="">Select Operation Name</option>';
        }
    });
    }

    // Open modal for editing operation
    window.openEditOperation = function(operation) {
        const modal = document.getElementById('OperationModel');
        const form = document.getElementById('addopform');
        const updateRoute = "{{ route('admin.operation.opnemeupdate', ':id') }}".replace(':id', operation.id);
        form.setAttribute('action', updateRoute);

        // Change button label if exists
        const submitBtn = document.querySelector('#addopform button[type="submit"]');
        if (submitBtn) submitBtn.textContent = "Update";

        // Add hidden input for PUT if not exists
        if (!form.querySelector('input[name="_method"]')) {
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            form.appendChild(methodInput);
        } else {
            form.querySelector('input[name="_method"]').value = 'PUT';
        }

        // Fill values
        document.getElementById('operation_date').value = operation.operation_date || '';
        document.getElementById('category_id').value = operation.category_id || '';

        // Fetch and populate operations based on category
        const operationSelect = document.getElementById('operation_id');
        const RouteTemplate = "{{ route('admin.operation.get-operations-by-category', ':id') }}".replace(':id', operation.category_id);
        fetch(RouteTemplate)
            .then(response => response.json())
            .then(data => {
                operationSelect.innerHTML = '<option value="">Select Operation Name</option>';
                data.forEach(op => {
                    const opt = document.createElement('option');
                    opt.value = op.id;
                    opt.textContent = op.name;
                    operationSelect.appendChild(opt);
                });
                operationSelect.value = operation.operation_id || '';
            });

        document.getElementById('doctor_id').value = operation.doctor_id || '';
        document.getElementById('assistant_consultant1').value = operation.assistant_consultant1 || '';
        document.getElementById('assistant_consultant2').value = operation.assistant_consultant2 || '';
        document.getElementById('anesthetist').value = operation.anesthetist || '';
        document.getElementById('anesthesia_type').value = operation.anesthesia_type || '';
        document.getElementById('ot_technician').value = operation.ot_technician || '';
        document.getElementById('ot_assistant').value = operation.ot_assistant || '';
        document.getElementById('remark').value = operation.remark || '';
        document.getElementById('result').value = operation.result || '';

        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('flex', 'overflow-y-auto');
        modal.scrollTop = 0;
    };
});
</script>
<script>
function openEditOperation(operation) {
    const modal = document.getElementById('OperationModel');


    // Set form action to update route
    const form = document.getElementById('addopform');
    const updateRoute = "{{ route('admin.operation.opnemeupdate', ':id') }}".replace(':id', operation.id);
    form.setAttribute('action', updateRoute);

    // Change button label
    document.getElementById('submitOperationBtn').textContent = "Update";

    // Add hidden input for PUT
    if (!document.querySelector('input[name="_method"]')) {
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);
    }

    // Fill values
    document.getElementById('operation_edit_id').value = operation.id;
    document.getElementById('operation_date').value = operation.operation_date || '';
    document.getElementById('category_id').value = operation.category_id || '';

    // Fetch and populate operations based on category
    const operationSelect = document.getElementById('operation_id');
    const RouteTemplate = "{{ route('admin.operation.get-operations-by-category', ':id') }}".replace(':id', operation.category_id);
    
    fetch(RouteTemplate)
        .then(response => response.json())
        .then(data => {
            operationSelect.innerHTML = '<option value="">Select</option>';
            data.forEach(op => {
                const opt = document.createElement('option');
                opt.value = op.id;
                opt.textContent = op.name;
                operationSelect.appendChild(opt);
            });
            operationSelect.value = operation.operation_id || '';
        });

    document.getElementById('doctor_id').value = operation.doctor_id || '';
    document.getElementById('assistant_consultant1').value = operation.assistant_consultant1 || '';
    document.getElementById('assistant_consultant2').value = operation.assistant_consultant2 || '';
    document.getElementById('anesthetist').value = operation.anesthetist || '';
    document.getElementById('anesthesia_type').value = operation.anesthesia_type || '';
    document.getElementById('ot_technician').value = operation.ot_technician || '';
    document.getElementById('ot_assistant').value = operation.ot_assistant || '';
    document.getElementById('remark').value = operation.remark || '';
    document.getElementById('result').value = operation.result || '';

    // Show modal
    modal.classList.remove('hidden');
    modal.classList.add('flex', 'overflow-y-auto');
}
</script>
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.getElementById(id).classList.add('flex');
        document.getElementById('OperationModel').classList.add('hidden');
        loadOperationName();
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');

        document.getElementById(id).classList.remove('flex');
    }

    function formSubmit2(event, form, closeAfter, formId) {
        event.preventDefault();
        const formData = new FormData(form);
        fetch(form.action, {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || "Saved successfully!");
            if (closeAfter) closeModal(form.parentElement.parentElement.id);
            form.reset();
            // Optional: reload dropdowns if necessary
        })
        .catch(err => console.error(err));
    }
</script>

<script>
    
     const operationCategoryList = document.getElementById('operation_category_list');
let urlcharge = "{{ route('admin.operation.cat.list') }}";
       

        fetch(urlcharge)
            .then(response => response.json())
            .then(data => {
                console.log("Operation cat data", data);

                let tableHtmlcat = `
                    <div class="overflow-x-auto">
                        <div class="flex justify-between items-center mb-4">
                           
                        </div>
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-200 text-gray-700 uppercase text-sm">
                                   
                                    <th class="p-3 text-left">Operation Category Name</th>
                                    <th class="p-3 text-left">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm">`;

                const deleteRouteTemplate = "{{ route('admin.operation.opnemedelete', ':id') }}";

                data.forEach(operation => {
                    const deleteUrl = deleteRouteTemplate.replace(':id', operation.id);

                    tableHtmlcat += `
                        <tr class="border-b hover:bg-gray-100">
                          
                            <td class="p-3">${operation ? operation.name : ''}</td>
                            
                           
    </button>
                             <form action="${deleteUrl}" method="POST" style="display:inline;" id="addoperationdelete" onsubmit="formSubmit2(event, this, false, 'addoperationdelete')" >
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                        <i class="fas fa-trash" style="color:red;"></i>
                                    </button>
                                </form>
                                
                            </td>
                        </tr>`;
                });

                tableHtmlcat += `
                            </tbody>
                        </table>
                    </div>`;

                operationCategoryList.innerHTML = tableHtmlcat;

                // Now bind the click event for the new "Add New Operation" button
                const addOpCatBtn = document.getElementById('openModaloperation');
                if (addOpCatBtn && modal) {
                    addOpCatBtn.addEventListener('click', function () {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex', 'overflow-y-auto');
                        modal.scrollTop = 0;
                    });
                }
            })
            .catch(error => {
                console.error("Error fetching operations:", error);
                operationCategoryList.innerHTML = `<p class="text-red-600">Failed to load operation data.</p>`;
            });

    </script>



<script>


// Function to load and display operation categories
function loadOperationCategories() {
    
    const operationCategoryList = document.getElementById('operation_category_list');
    if (!operationCategoryList) return;

    let urlcharge = "{{ route('admin.operation.cat.list') }}";

    fetch(urlcharge)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log("Operation category data", data);

            if (!Array.isArray(data)) {
                throw new Error('Invalid data format received');
            }

            let tableHtmlcat = `
                <div class="mt-4">
                    <h3 class="text-md font-medium mb-2">Existing Categories</h3>
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 uppercase text-xs">
                                <th class="p-2 text-left">Category Name</th>
                                <th class="p-2 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm">`;

            if (data.length === 0) {
                tableHtmlcat += `
                    <tr>
                        <td colspan="2" class="p-2 text-center text-gray-500">No categories found</td>
                    </tr>`;
            } else {
                data.forEach(category => {
                    const deleteUrl = "{{ route('admin.operation.category.delete', ':id') }}".replace(':id', category.id);
                    
                    tableHtmlcat += `
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-2">${category.name || ''}</td>
                            <td class="p-2">
                                <form action="${deleteUrl}" method="POST" style="display:inline;" id="operationcatDelete" onsubmit="PostWithoutReload(event, this, true, 'operationcatDelete','loadOperationCategories')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>`;
                });
            }

            tableHtmlcat += `</tbody></table></div>`;
            operationCategoryList.innerHTML = tableHtmlcat;
        })
        .catch(error => {
            console.error("Error fetching categories:", error);
            operationCategoryList.innerHTML = `
                <div class="p-4 bg-red-50 text-red-600 rounded">
                    Failed to load categories. Please try again.
                </div>`;
        });
}
function loadOperationName() {
    const operationCategoryList = document.getElementById('operation_name_list');
    if (!operationCategoryList) return;

    let urlcharge = "{{ route('admin.operation.name.list') }}";

    fetch(urlcharge)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log("Operation Name data", data);

            if (!Array.isArray(data)) {
                throw new Error('Invalid data format received');
            }

            let tableHtmlcat = `
                <div class="mt-4">
                    <h3 class="text-md font-medium mb-2">Existing Categories</h3>
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 uppercase text-xs">
                                <th class="p-2 text-left">Operation Name</th>
                                <th class="p-2 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm">`;

            if (data.length === 0) {
                tableHtmlcat += `
                    <tr>
                        <td colspan="2" class="p-2 text-center text-gray-500">No categories found</td>
                    </tr>`;
            } else {
                data.forEach(operation => {
                    const deleteUrl = "{{ route('admin.operation.name.delete', ':id') }}".replace(':id', operation.id);
                    
                    tableHtmlcat += `
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-2">${operation.name || ''}</td>
                            <td class="p-2">
                                <form action="${deleteUrl}" method="POST" style="display:inline;" id="operationNameDelete" onsubmit="PostWithoutReload(event, this, true, 'operationNameDelete','loadOperationName')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>`;
                });
            }

            tableHtmlcat += `</tbody></table></div>`;
            operationCategoryList.innerHTML = tableHtmlcat;
        })
        .catch(error => {
            console.error("Error fetching categories:", error);
            operationCategoryList.innerHTML = `
                <div class="p-4 bg-red-50 text-red-600 rounded">
                    Failed to load categories. Please try again.
                </div>`;
        });
}



</script> 
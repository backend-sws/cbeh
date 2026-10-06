@extends('layouts.admin')

@section('title', 'New Pathology Test')

@section('content')

    <body class="bg-gray-50 p-6 font-sans">
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>




        <div class="mt-2 card bg-white p-4 rounded-lg md:flex-row justify-between items-start md:items-center ">
            <div class="flex justify-between items-center w-full">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">New Pathology Test</h2>
                    <p class="text-sm text-gray-600 mt-1">Create new pathology test with parameters</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ url()->previous() }}">
                        <button
                            class="flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md text-sm font-medium transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                            Back
                        </button>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div>
            <!-- Alerts -->
            <div class="mb-6">
                <x-alert type="success" :message="session('success')" />
                <x-alert type="error" :message="session('error')" :errorsop="session('errorsop')" />
            </div>

            <!-- Form Container -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <form id="pathology_test_form" method="post" action="{{ route('admin.pathology.test.save') }}">
                    @csrf
                    <div class="p-6 space-y-6">
                        <!-- Basic Information Section -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Test Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <!-- Test Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Test Name <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="test_name" placeholder="Enter test name"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required />
                                </div>

                                <!-- Short Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Short Name <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="short_name" placeholder="Enter short name"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required />
                                </div>

                                <!-- Test Type -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Test Type <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="test_type" placeholder="Enter test type"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required />
                                </div>

                                <!-- Category -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Category <span
                                            class="text-red-500">*</span></label>
                                    <select name="category_id"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required>
                                        <option value="" selected disabled>Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information Section -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Additional Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <!-- Sub Category -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Category</label>
                                    <input type="text" name="sub_category" placeholder="Enter sub category"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                </div>

                                <!-- Method -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Method</label>
                                    <input type="text" name="method" placeholder="Enter method"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                </div>

                                <!-- Report Days -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Report Days <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="report_days" placeholder="Enter days"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required />
                                </div>
                            </div>
                        </div>

                        <!-- Pricing Section -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Pricing</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <!-- Charge Category -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Charge Category <span
                                            class="text-red-500">*</span></label>
                                    <select name="charge_category" id="charge_category"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required>
                                        <option value="" selected disabled>Select Charge Category</option>
                                        @foreach ($charge_category as $category)
                                            <option value="{{ $category->id }}">{{ $category->charge_category }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Standard Charge -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Standard Charge (₹) <span
                                            class="text-red-500">*</span></label>

                                    {{-- Update: Added 'readonly' attribute and 'bg-gray-100' class --}}
                                    <input type="text" name="standard_charge" id="standard_charge" placeholder="0.00"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100 cursor-not-allowed"
                                        readonly required />
                                </div>

                                <!-- Tax -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tax (₹)</label>
                                    <input type="text" name="tax" id="tax" placeholder="0.00"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                </div>
                            </div>
                        </div>

                        <!-- Parameters Section -->
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-800">Test Parameters</h3>
                                <button type="button" id="addRowButton"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center transition duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Add Parameter
                                </button>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Parameter Name <span class="text-red-500">*</span></th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Reference Range <span class="text-red-500">*</span></th>
                                            <th scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Unit <span class="text-red-500">*</span></th>
                                            <th scope="col"
                                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="parameterRows" class="bg-white divide-y divide-gray-200">
                                        <tr class="parameter-row">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <select
                                                    class="parameter-select w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    <option value="">Select Parameter</option>
                                                    @foreach ($parameters as $param)
                                                        <option value="{{ $param->id }}"
                                                            data-ref="{{ $param->reference_range }}"
                                                            data-unit="{{ $param->unit->name ?? '' }}">
                                                            {{ $param->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" class="patient-result" value="0" />
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="text"
                                                    class="reference-range w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100"
                                                    readonly />
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="text"
                                                    class="unit w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100"
                                                    readonly />
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <button type="button" class="text-red-600 hover:text-red-800"
                                                    onclick="removeRow(this)">
                                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            d="M9 3v1H4v2h1v14a2 2 0 002 2h10a2 2 0 002-2V6h1V4h-5V3H9zm2 5v10h2V8h-2z" />
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Hidden input for parameters -->
                        <input type="hidden" name="parameter" id="parameter-json">

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md flex items-center transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Save Test
                            </button>
                            <a href="{{ url()->previous() }}">
                                <button type="button"
                                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-md flex items-center transition duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Cancel
                                </button>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
@endsection

@push('js')
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>

    <script>
        const allParameters = @json($parameters);

        function syncParameterJson() {
            const data = [];
            document.querySelectorAll('.parameter-row').forEach(row => {
                const paramSelect = row.querySelector('.parameter-select');
                const resultInput = row.querySelector('.patient-result');

                if (paramSelect && resultInput) {
                    const paramId = paramSelect.value;
                    const patientResult = resultInput.value.trim();

                    if (paramId && patientResult) {
                        data.push({
                            parameter_id: parseInt(paramId),
                            patient_result: patientResult
                        });
                    }
                }
            });

            const hiddenInput = document.getElementById('parameter-json');
            if (hiddenInput) {
                hiddenInput.value = JSON.stringify(data);
            }
        }

        function getSelectedParameterIds() {
            return Array.from(document.querySelectorAll('.parameter-select'))
                .map(select => select.value)
                .filter(id => id !== "");
        }

        function renderParameterOptions(selectedIds = [], selectedValue = '') {
            return `<option value="">Select Parameter</option>` +
                allParameters.map(p => {
                    const isUsed = selectedIds.includes(p.id.toString());
                    const selected = (p.id.toString() === selectedValue) ? 'selected' : '';
                    if (!isUsed || selected) {
                        return `<option value="${p.id}" data-ref="${p.reference_range}" data-unit="${p.unit?.name ?? ''}" ${selected}>
                              ${p.name}
                          </option>`;
                    }
                    return '';
                }).join('');
        }

        function updateAllSelectOptions() {
            const selectedIds = getSelectedParameterIds();
            document.querySelectorAll('.parameter-select').forEach(select => {
                const currentValue = select.value;
                select.innerHTML = renderParameterOptions(selectedIds.filter(id => id !== currentValue),
                    currentValue);
            });
            toggleAddButton();
        }

        function toggleAddButton() {
            const selectedIds = getSelectedParameterIds();
            const addButton = document.getElementById('addRowButton');
            if (addButton) {
                addButton.style.display = selectedIds.length >= allParameters.length ? 'none' : 'inline-flex';
            }
        }

        function addRow() {
            if (!validateExistingRows()) {
                return; // stop if current rows are invalid
            }

            const parameterRows = document.getElementById('parameterRows');
            const selectedIds = getSelectedParameterIds();

            const newRow = document.createElement('tr');
            newRow.classList.add('parameter-row');
            newRow.innerHTML = `
          <td class="px-6 py-4 whitespace-nowrap">
              <select class="parameter-select w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                  ${renderParameterOptions(selectedIds)}
              </select>
              <input type="hidden" class="patient-result" value="0" />
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
              <input type="text" class="reference-range w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" readonly />
          </td>
          <td class="px-6 py-4 whitespace-nowrap">
              <input type="text" class="unit w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" readonly />
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-right">
              <button type="button" class="text-red-600 hover:text-red-800 remove-btn">
                  <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M9 3v1H4v2h1v14a2 2 0 002 2h10a2 2 0 002-2V6h1V4h-5V3H9zm2 5v10h2V8h-2z"/>
                  </svg>
              </button>
          </td>
      `;

            parameterRows.appendChild(newRow);
            bindDynamicEvents(newRow);
            updateAllSelectOptions();
            syncParameterJson();
        }

        function validateExistingRows() {
            let isValid = true;

            document.querySelectorAll('.parameter-row').forEach(row => {
                const paramSelect = row.querySelector('.parameter-select');
                const resultInput = row.querySelector('.patient-result');

                // Clear previous error styles
                paramSelect?.classList.remove('border-red-500');
                resultInput?.classList.remove('border-red-500');

                const paramId = paramSelect?.value?.trim();
                const resultVal = resultInput?.value?.trim();

                // Validate only if row has any input
                if (paramId || resultVal) {
                    if (!paramId) {
                        paramSelect?.classList.add('border-red-500');
                        isValid = false;
                    }
                    if (!resultVal) {
                        resultInput?.classList.add('border-red-500');
                        isValid = false;
                    }
                }
            });

            return isValid;
        }

        function bindDynamicEvents(row) {
            const paramSelect = row.querySelector('.parameter-select');
            const resultInput = row.querySelector('.patient-result');
            const removeBtn = row.querySelector('.remove-btn');

            if (paramSelect) {
                paramSelect.addEventListener('change', e => {
                    const selected = e.target.options[e.target.selectedIndex];
                    const row = e.target.closest('.parameter-row');
                    const refRangeInput = row.querySelector('.reference-range');
                    const unitInput = row.querySelector('.unit');

                    if (refRangeInput) refRangeInput.value = selected.dataset.ref || '';
                    if (unitInput) unitInput.value = selected.dataset.unit || '';

                    if (e.target.value !== "") {
                        resultInput.setAttribute('required', 'required');
                    } else {
                        resultInput.removeAttribute('required');
                    }

                    syncParameterJson();
                    updateAllSelectOptions();
                });
            }

            if (resultInput) {
                resultInput.addEventListener('input', () => {
                    resultInput.classList.remove('border-red-500'); // Clear error
                    syncParameterJson();
                });
            }

            if (removeBtn) {
                removeBtn.addEventListener('click', e => {
                    const row = e.target.closest('.parameter-row');
                    if (row) {
                        row.remove();
                        updateAllSelectOptions();
                        syncParameterJson();
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.parameter-row').forEach(row => bindDynamicEvents(row));
            updateAllSelectOptions();
            syncParameterJson();

            const addButton = document.getElementById('addRowButton');
            if (addButton) {
                addButton.addEventListener('click', addRow);
            }

            const form = document.getElementById('pathology_test_form');
            if (form) {
                form.addEventListener('submit', () => {
                    syncParameterJson();
                });
            }
        });

        $('select[name="charge_category"]').on('change', function() {
            const selectedValue = $(this).val();
            $('input[name="standard_charge"]').val(selectedValue);
        });

        let valid_form = ['pathology_test_form'];
        let formId = $('.form-class').prop('id');

        function validateForm() {
            if (!valid_form.includes(formId)) return;

            var form = $("#" + formId);
            form.validate({
                ignore: [],
                rules: {
                    patient_id: {
                        required: true
                    },
                    test_name: {
                        required: true
                    },
                    short_name: {
                        required: true
                    },
                    test_type: {
                        required: true
                    },
                    category_id: {
                        required: true
                    },
                    unit_id: {
                        required: true
                    },
                    sub_category: {
                        maxlength: 255
                    },
                    method: {
                        maxlength: 255
                    },
                    report_days: {
                        digits: true,
                        maxlength: 3
                    }
                },
                messages: {
                    patient_id: {
                        required: "Please select a patient"
                    },
                    test_name: {
                        required: "Please enter the test name"
                    },
                    short_name: {
                        required: "Please enter the short name"
                    },
                    test_type: {
                        required: "Please enter the test type"
                    },
                    category_id: {
                        required: "Please select a category"
                    },
                    unit_id: {
                        required: "Please select a unit"
                    },
                    sub_category: {
                        maxlength: "Sub category must be less than 255 characters"
                    },
                    method: {
                        maxlength: "Method must be less than 255 characters"
                    },
                    report_days: {
                        digits: "Report days must be a number",
                        maxlength: "Report days must be less than 3 digits"
                    }
                },
                errorPlacement: function(error, element) {
                    error.addClass('text-danger text-sm');
                    error.insertAfter(element);
                },
                errorElement: 'label',
                submitHandler: function(form) {
                    syncParameterJson();
                    form.submit();
                }
            });
        }

        validateForm();

        const Hospitalcharge = document.getElementById('charge_category');

        Hospitalcharge.addEventListener('change', () => {
            const chargeCategoryId = Hospitalcharge.value;

            if (!chargeCategoryId) return;

            fetch(`{{ url('admin/charge/add-charge-data/') }}/${chargeCategoryId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('charge data hos =', data);
                    document.getElementById('standard_charge').value = data.standard_charge || '';
                })
                .catch(err => console.error('Error loading charge data:', err));
        });
    </script>
@endpush

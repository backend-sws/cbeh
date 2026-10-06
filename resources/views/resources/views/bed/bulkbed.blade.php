@extends('layouts.admin')

@section('title', 'Beds')

@section('content')
   
    <style>
        /* Custom override to match Tailwind-style inputs */
        .select2-container .select2-selection--single {
            height: 40px !important; /* Tailwind “p-2” ≈ 40px total */
            padding: 6px 12px;
            border: 1px solid #d1d5db; /* Tailwind’s gray-300 */
            border-radius: 0.375rem; /* Tailwind’s rounded-md */
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: normal;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            top: 0;
            right: 8px;
        }
    </style>

    <body class="bg-gray-100 p-10">
        <div class="p-6 bg-white rounded-2xl shadow-md">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-700">
                    <thead class="bg-gray-100 text-gray-600 font-semibold">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">
                                BED<span class="text-red-500">*</span>
                            </th>
                            <th class="px-4 py-3">
                                BED TYPE<span class="text-red-500">*</span>
                            </th>
                            <th class="px-4 py-3">
                                CHARGE<span class="text-red-500">*</span>
                            </th>
                            <th class="px-4 py-3">DESCRIPTION</th>
                            <th class="px-4 py-3 text-right">
                                <button
                                    onclick="addRow()"
                                    class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600"
                                >
                                    Add
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="bedTableBody">
                        {{-- Rows get injected here --}}
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <button class="bg-indigo-500 text-white px-6 py-2 rounded-md hover:bg-indigo-600">
                    Save
                </button>
                <button class="bg-gray-300 text-gray-800 px-6 py-2 rounded-md hover:bg-gray-400">
                    Cancel
                </button>
            </div>
        </div>

        <script>
            // rowCount = how many rows are visible (for numbering)
            let rowCount = 0;

            // nextRowId = a never‐decrementing counter used to give each <select> a unique ID
            let nextRowId = 1;

            function addRow() {
                // 1) Increment the visible row counter
                rowCount++;

                // 2) Grab the <tbody> element
                const tbody = document.getElementById("bedTableBody");

                // 3) Decide on a unique ID for this new <select>
                const thisRowId = nextRowId++;
                const selectId = `bedType-${thisRowId}`;

                // 4) Build a new <tr>
                const row = document.createElement("tr");
                row.classList.add("border-t");

                // 5) Fill in its innerHTML (including the unique selectId)
                row.innerHTML = `
                    <td class="px-4 py-3">${rowCount}</td>
                    <td class="px-4 py-3">
                        <input
                            type="text"
                            placeholder="Bed"
                            class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-200"
                        />
                    </td>
                    <td class="px-4 py-3">
                        <select
                            id="${selectId}"
                            class="select2-dropdown w-full border rounded-md focus:outline-none"
                        >
                            <option disabled selected>Select Bed Type</option>
                            <option>Single</option>
                            <option>Double</option>
                            <option>Queen</option>
                            <option>King</option>
                        </select>
                    </td>
                    <td class="px-4 py-3">
                        <input
                            type="text"
                            placeholder="Charge"
                            class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-200"
                        />
                    </td>
                    <td class="px-4 py-3">
                        <textarea
                            placeholder="Description"
                            class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-200"
                        ></textarea>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <button
                            onclick="deleteRow(this)"
                            class="text-red-500 hover:text-red-700"
                        >
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;

                // 6) Append it to the <tbody>
                tbody.appendChild(row);

                // 7) Initialize Select2 on the newly-injected <select>
                //    (guaranteed to be unique because it uses nextRowId)
                $(`#${selectId}`).select2({
                    width: "100%"
                });
            }

            function deleteRow(buttonEl) {
                // Remove the <tr> that contains this button
                const row = buttonEl.closest("tr");

                // Before removing the row from DOM, it’s a good idea to destroy that Select2 instance:
                //   (we can find the <select> inside this row and .select2('destroy') to clean up)
                const $sel = $(row).find("select.select2-dropdown");
                if ($sel.length) {
                    $sel.select2("destroy");
                }

                // Now remove the row
                row.remove();

                // Finally, re-number all visible rows
                updateRowNumbers();
            }

            function updateRowNumbers() {
                const rows = document.querySelectorAll("#bedTableBody tr");
                rowCount = 0;
                rows.forEach((row) => {
                    rowCount++;
                    row.querySelector("td").textContent = rowCount;
                });
            }

            // Optionally add an initial row on page load:
            window.onload = function() {
                addRow();
            };
        </script>
    </body>
@endsection

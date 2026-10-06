@extends('layouts.admin')

@section('title', 'Blood Issue List')

@section('content')
<body class="bg-gray-50 p-6 font-sans">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="flex card bg-white p-6 flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Blood Issue Management</h1>
                <p class="text-gray-600">Track and manage blood issuance records</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                <button class="flex items-center justify-center space-x-2 px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                    <i class="ph ph-funnel text-lg"></i>
                    <span>Filter</span>
                </button>
                <button onclick="toggleModal(true)" 
                    class="flex items-center justify-center space-x-2 px-4 py-2 bg-pink-600 text-white rounded-md hover:bg-pink-700 transition-colors">
                    <i class="ph ph-plus text-lg"></i>
                    <span>New Blood Issue</span>
                </button>
            </div>
        </div>

        <div class="card bg-white p-6">

        <!-- Alerts -->
        <div class="mb-6">
            <x-alert type="success" :message="session('success')" />
            <x-alert type="error" :message="session('error')" :errorsop="session('errorsop')" />
        </div>

        <!-- Table Section -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient Details</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Issue Details</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donor</th>
                            {{-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th> --}}
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($bloodIssue as $issue)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-pink-100 flex items-center justify-center">
                                        <i class="ph ph-user text-pink-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $issue->patient_name }}</div>
                                        <div class="text-sm text-gray-500">ID: {{ $issue->patient_id ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $issue->doctor_name }}</div>
                                <div class="text-sm text-gray-500">Department</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($issue->created_at)->format('jS M Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($issue->created_at)->format('g:i A') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $issue->blood_group == 'O-' ? 'bg-red-100 text-red-800' : 
                                           ($issue->blood_group == 'AB+' ? 'bg-purple-100 text-purple-800' : 
                                           'bg-blue-100 text-blue-800') }}">
                                        {{ $issue->blood_group }}
                                    </span>
                                    <span class="ml-2 text-sm text-gray-900">{{ $issue->donor_name }}</span>
                                </div>
                            </td>
                            {{-- <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button class="text-pink-600 hover:text-pink-900 mr-3">
                                    <i class="ph ph-eye text-lg"></i>
                                </button>
                                <button class="text-blue-600 hover:text-blue-900">
                                    <i class="ph ph-pencil-simple-line text-lg"></i>
                                </button>
                            </td> --}}
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No blood issue records found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
                
            </div>
              <!-- Pagination -->
        <div class="mt-6">
            @include('partials.pagination',['users' => $bloodIssue])
        </div>
        </div>

      
    </div>

    <!-- Modal -->
    <div id="bloodIssueModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="bg-white w-full max-w-2xl rounded-lg shadow-xl overflow-hidden">
            <div class="flex justify-between items-center bg-gray-50 px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800">New Blood Issue</h2>
                <button onclick="toggleModal(false)" class="text-gray-400 hover:text-gray-500">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>

            <form class="space-y-4 form-class" id="issue_new_blood" method="post" action="{{route('admin.blood.issue_save')}}">
                @csrf 
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Donor Name <span class="text-red-500">*</span></label>
                        <input type="text" name="donor_name" value="{{ old('donor_name') }}" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent" 
                            placeholder="Enter donor name">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Patient Name <span class="text-red-500">*</span></label>
                        <input type="text" name="patient_name" value="{{ old('patient_name') }}" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent" 
                            placeholder="Enter patient name">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Doctor Name <span class="text-red-500">*</span></label>
                        <input type="text" name="doctor_name" value="{{ old('doctor_name') }}" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent" 
                            placeholder="Enter doctor name">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Blood Group <span class="text-red-500">*</span></label>
                        <select name="blood_group" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                            <option value="">Select Blood Group</option>
                            <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                            <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Amount (ml) <span class="text-red-500">*</span></label>
                        <input type="number" name="amount" value="{{ old('amount') }}" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent" 
                            placeholder="Enter amount in ml">
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Remarks <span class="text-red-500">*</span></label>
                        <textarea name="remarks" rows="3" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent" 
                            placeholder="Enter remarks">{{ old('remarks') }}</textarea>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 border-t flex justify-end space-x-3">
                    <button type="button" onclick="toggleModal(false)" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                        Save Blood Issue
                    </button>
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
    function toggleModal(show) {
        const modal = document.getElementById('bloodIssueModal');
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }
    }

    let valid_form = ['issue_new_blood'];
    var formId = $('.form-class').prop('id');

    function validateForm() {
        if (!valid_form.includes(formId)) {
            console.log('Form content not found!');
            return 0;
        }

        var form = $("#" + formId);

        form.validate({
            rules: {
                donor_name: { required: true },
                patient_name: { required: true },
                doctor_name: { required: true },
                blood_group: { required: true },
                amount: { required: true, number: true, min: 1 },
                remarks: { required: true }
            },
            messages: {
                donor_name: { required: "Please enter the donor's name" },
                patient_name: { required: "Please enter the patient's name" },
                doctor_name: { required: "Please enter the doctor's name" },
                blood_group: { required: "Please select blood group" },
                amount: { 
                    required: "Please enter the amount",
                    number: "Amount must be a number",
                    min: "Amount must be at least 1"
                },
                remarks: { required: "Please enter remarks" }
            },
            errorElement: 'p',
            errorClass: 'mt-1 text-sm text-red-600',
            highlight: function(element) {
                $(element).addClass('border-red-500').removeClass('border-gray-300');
                $(element).nextAll('.error').remove();
            },
            unhighlight: function(element) {
                $(element).removeClass('border-red-500').addClass('border-gray-300');
            },
            errorPlacement: function(error, element) {
                if (element.attr('type') === 'radio' || element.attr('type') === 'checkbox') {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                $('button[type="submit"]').prop('disabled', true).html('<i class="ph ph-circle-notch animate-spin"></i> Processing...');
                form.submit();
            }
        });
    }

    $(document).ready(function() {
        validateForm();
    });
</script>
@endpush
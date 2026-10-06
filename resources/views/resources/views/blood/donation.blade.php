@extends('layouts.admin')

@section('title', 'Blood Donation List')

@section('content')
<body class="bg-gray-50 p-6 font-sans">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="flex card bg-white p-6 flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Blood Donation Management</h1>
                <p class="text-gray-600">Track and manage blood donation records</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                <button class="flex items-center justify-center space-x-2 px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                    <i class="ph ph-funnel text-lg"></i>
                    <span>Filter</span>
                </button>
                <button onclick="toggleBloodGroupModal(true)" 
                    class="flex items-center justify-center space-x-2 px-4 py-2 bg-pink-600 text-white rounded-md hover:bg-pink-700 transition-colors">
                    <i class="ph ph-plus text-lg"></i>
                    <span>New Donation</span>
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
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donor Details</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donation</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($bloodDonation as $donation)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-pink-100 flex items-center justify-center">
                                        <i class="ph ph-user text-pink-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $donation->donor_name }}</div>
                                        {{-- <div class="text-sm text-gray-500">arvind2024@gmail.com</div> --}}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $donation->bags }} bag(s)
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($donation->updated_at)->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="toggleBloodGroupModal(true,this,'edit')" 
                                    data-url="{{route('admin.blood.donation_update',$donation->id)}}" 
                                    data-bags="{{$donation->bags}}" 
                                    data-donorname="{{$donation->donor_name}}"
                                    class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="ph ph-pencil-simple text-lg"></i>
                                </button>
                                <button onclick="toggleBloodGroupModal(true,this,'delete')" 
                                    data-url="{{route('admin.blood.donation_delete',$donation->id)}}"
                                    class="text-red-600 hover:text-red-900">
                                    <i class="ph ph-trash text-lg"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                No blood donation records found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            @include('partials.pagination',['users' => $bloodDonation])
        </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="bloodGroupModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="bg-white w-full max-w-md rounded-lg shadow-xl overflow-hidden">
            <div class="flex justify-between items-center bg-gray-50 px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800 modal-header">New Blood Donation</h2>
                <button onclick="toggleBloodGroupModal(false)" class="text-gray-400 hover:text-gray-500">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>

            <form class="form-class" id="donation_form" method="post" action="{{route('admin.blood.donation_save')}}" data-action="{{route('admin.blood.donation_save')}}">
                @csrf 
                <div class="p-6 space-y-4 main-fields">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Donor Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="donor_name" id="donor_name" value="{{ old('donor_name') }}" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent" 
                            placeholder="Enter donor name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Bags <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="bags" id="bags" value="{{ old('bags') }}" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent" 
                            placeholder="Enter number of bags">
                    </div>
                </div>
                <div class="p-6 delete-msg" style="display:none;">
                    <p class="text-gray-700">Are you sure you want to delete this record?</p>
                </div>
                
                <div class="bg-gray-50 px-6 py-4 border-t flex justify-end space-x-3">
                    <button type="button" onclick="toggleBloodGroupModal(false)" 
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                        <span class="submit-text">Save</span>
                        <span class="loading hidden"><i class="ph ph-circle-notch animate-spin"></i> Processing...</span>
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
    function toggleBloodGroupModal(show, tag = null, actionType = null) {
        const modal = document.getElementById('bloodGroupModal');
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');

            if(tag && (actionType == 'edit')) {
                let element = $(tag);
                if(element) {
                    let url = element.data('url');
                    let bags = element.data('bags');
                    let donor_name = element.data('donorname');
                    if(url && donor_name && bags) {
                        $('.modal-header').text('Edit Blood Donation');
                        $('.main-fields').show();
                        $('.delete-msg').hide();
                        $('#donor_name').val(donor_name);
                        $('#bags').val(bags);
                        $('#donation_form').attr('action', url);
                        $('.submit-text').text('Update');
                    }
                }
            } else if(tag && (actionType == 'delete')) {
                let element = $(tag);
                if(element) {
                    let url = element.data('url');
                    if(url) {
                        $('.modal-header').text('Delete Blood Donation');
                        $('.main-fields').hide();
                        $('.delete-msg').show();
                        $('#donation_form').attr('action', url);
                        $('.submit-text').text('Delete');
                    }
                }
            }
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');

            $('.modal-header').text('New Blood Donation');
            $('.main-fields').show();
            $('.delete-msg').hide();
            $('#donation_form').attr('action', $('#donation_form').data('action'));
            $('#donation_form')[0].reset();
            $('.submit-text').text('Save');
            $('.loading').addClass('hidden');
        }
    }

    let valid_form = ['donation_form'];
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
                bags: { required: true, number: true, min: 1 }
            },
            messages: {
                donor_name: { required: "Please enter the donor's name" },
                bags: { 
                    required: "Please enter the number of bags",
                    number: "Must be a valid number",
                    min: "Must be at least 1 bag"
                }
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
                error.insertAfter(element);
            },
            submitHandler: function(form) {
                $('.submit-text').addClass('hidden');
                $('.loading').removeClass('hidden');
                form.submit();
            }
        });
    }

    $(document).ready(function() {
        validateForm();
    });
</script>
@endpush
@extends('layouts.admin')

@section('title', 'Blood Donor List')

@section('content')


<body class="bg-gray-50 p-6 font-sans ">

 <div class="flex justify-between items-center mb-4 ">
            <h1 class="text-2xl font-bold text-gray-800">Blood Donor</h1>
            <div class="flex space-x-2">
                <button class="flex items-center customborder border-main-color space-x-2 px-4 py-2 bg-pink-200 text-gray-700 rounded-md hover:bg-gray-300">
                    <i class="ph ph-funnel"></i> <!-- Filter Icon -->
                </button>
                 <button onclick="toggleBloodGroupModal(true)" class="text-main-color bg-pink-200 customborder border-main-color px-6 py-2 rounded-lg flex items-center" onclick="window.location.href='/newipdpatient'">
                            New Blood Donor
                        </button>
                      
                
            </div>
        </div>
<div class="overflow-x-auto mt-6">
  <!-- msg -->
  <x-alert type="success" :message="session('success')" />
    <x-alert type="error" :message="session('error')" :errorsop="session('errorsop')" />

  <table class="min-w-full text-sm text-left border-collapse">
    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
      <tr>
        <th class="px-6 py-4">Patient </th>
        <th class="px-6 py-4">Blood Group </th>
        <th class="px-6 py-4">Age </th>
        <th class="px-6 py-4">Gender </th>
        <th class="px-6 py-4">Last Donation Date</th>
        <th class="px-6 py-4">Action</th>
      </tr>
    </thead>
    <tbody class="text-gray-700">
    @foreach($bloodDonor as $donor)
      <!-- Row -->
      <tr class="bg-gray-50 border-b">
        <td class="px-6 py-4">
          <div>
            <div class="font-semibold">{{$donor->name}}</div>
            <div class="text-sm text-gray-500">arivind2024@gmail.com</div>
          </div>
        </td>
        <td class="px-6 py-4">
          <span class="bg-red-100 text-red-600 text-xs font-semibold px-3 py-1 rounded-full">{{$donor->blood_group}}</span>
        </td>
        <td class="px-6 py-4">
          <span class="bg-blue-100 border border-blue-500 text-blue-600 text-xs font-semibold px-3 py-1 rounded">{{$donor->age}}</span>
        </td>
        <td class="px-6 py-4">
          <span class="bg-orange-100 text-orange-600 text-xs font-semibold px-3 py-1 rounded border border-orange-400">{{$donor->gender}}</span>
        </td>
        <td class="px-6 py-4">
          <span class="bg-pink-100 text-pink-600 text-xs font-semibold px-4 py-1 rounded border border-pink-500 block text-center">
          {{ \Carbon\Carbon::parse($donor->last_donation_date)->format('g:i A') }}<br>{{ \Carbon\Carbon::parse($donor->last_donation_date)->format('jS M Y') }}
          </span>
        </td>
        <td class="px-6 py-4 flex space-x-2">
          <button onclick="toggleBloodGroupModal(true,this,'edit')" data-url="{{route('admin.blood.donor_update',$donor->id)}}" data-name="{{$donor->name}}" data-age="{{$donor->age}}" data-blood-group="{{$donor->blood_group}}" data-gender="{{$donor->gender}}" data-last-donation-date="{{$donor->last_donation_date}}" class="text-gray-700 dark:text-gray-500 hover:text-green-500 rounded-md p-2 customborder border-main-color">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button onclick="toggleBloodGroupModal(true,this,'delete')" data-url="{{route('admin.blood.donor_delete',$donor->id)}}" class="text-gray-700 dark:text-gray-500 hover:text-red-500 rounded-md p-2 customborder border-main-color">
                                <i class="fa-solid fa-trash"></i>
                            </button>
        </td>
      </tr>
      @endforeach
      <!-- Duplicate this <tr> block for other rows like Rahul Kumar, Prusotam Pandit -->
      <!-- Modify the name, email, blood group, gender etc. -->

    </tbody>
  </table>
</div>


    

   <!-- Pagination -->
         @include('partials.pagination',['users' => $bloodDonor])
        <br>
</body>


<!-- Modal -->
<div id="bloodGroupModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
  <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-md relative">
    
    <!-- Header -->
    <div class="flex justify-between items-center border-b pb-3 mb-4">
      <h2 class="text-lg font-semibold modal-header">New Blood Donor</h2>
      <button onclick="toggleBloodGroupModal(false)" class="text-2xl text-gray-500 hover:text-black">&times;</button>
    </div>

    <!-- Form -->
 <form class="space-y-4 form-class"  id="donor_form" method="post"  action="{{route('admin.blood.donor_save')}}" data-action="{{route('admin.blood.donor_save')}}">
    @csrf      
    <div class="mt-2 main-fields">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="text-sm font-medium text-gray-700">Name: <span class="text-red-500">*</span></label>
              <input type="text" name="name" placeholder="Enter here..." class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-600">
            </div>
            <div>
              <label class="text-sm font-medium text-gray-700">Age: <span class="text-red-500">*</span></label>
              <input type="number" name="age" placeholder="Enter here..." class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-600">
            </div>
            <div>
              <label class="text-sm font-medium text-gray-700">Blood Group: <span class="text-red-500">*</span></label>
              <input type="text" name="blood_group" placeholder="Enter here..." class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-600">
            </div>
            <div class="gender-box">
              <label class="text-sm font-medium text-gray-700">Gender: <span class="text-red-500">*</span></label>
              <div class="flex items-center mt-1 space-x-4">
                <label class="flex items-center space-x-1">
                  <input type="checkbox" name="gender" value="male"  class="gender-checkbox accent-green-500 border rounded border-green-600">
                  <span>Male</span>
                </label>
                <label class="flex items-center space-x-1">
                  <input type="checkbox" name="gender" value="female" class="gender-checkbox accent-gray-500 border rounded">
                  <span>Female</span>
                </label>
              </div>
            </div>
            <div>
              <label class="text-sm font-medium text-gray-700">Last Donation Date: <span class="text-red-500">*</span></label>
              <input type="date" name="last_donation_date" placeholder="Enter here..." class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-600">
            </div>
      </div>
    </div>
    <div class="mt-2 delete-msg" style="display:none;">
        Are you Sure you want to Delete this Record ?
    </div>
      <!-- Buttons -->
      <div class="flex justify-end space-x-4 mt-6">
        <button type="submit" class="bg-purple-700 hover:bg-purple-800 text-white px-6 py-2 rounded shadow">
          Save
        </button>
        <button type="button" onclick="document.getElementById('donorModal').classList.add('hidden')" class="bg-gray-200 hover:bg-gray-300 text-black px-6 py-2 rounded shadow">
          Cancel
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('js')
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script>
   document.querySelectorAll('.gender-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function () {
      if (!this.checked) {
        return;
      }
      document.querySelectorAll('.gender-checkbox').forEach(box => {
        if (box !== this) box.checked = false;
      });
      $("#donor_form").validate().element('input[name="gender"]');
    });
  });
  function toggleBloodGroupModal(show,tag=null,actionType=null) {
    const modal = document.getElementById('bloodGroupModal');
    if (show) { // edit & delete
      modal.classList.remove('hidden');
      modal.classList.add('flex');

      if(tag && (actionType =='edit')){ // edit
        // console.log(tag,$(tag).data('bags'));
        let element=$(tag);
        if(element){
          let url=element.data('url');
          let name=element.data('name');
          let age=element.data('age');
          let gender=element.data('gender');
          let blood_group=element.data('blood-group');
          let lastDonationDate=element.data('last-donation-date');
          if(url){
            $('.modal-header').html('Edit Blood Donor');
            $('.main-fields').show();
            $('.delete-msg').hide();
            $('input[name="name"]').val(name);
            $('input[name="age"]').val(age);
            $('input[name="gender"]').each(function() {
              $(this).prop('checked', $(this).val() === gender);
            });
            $('input[name="blood_group"]').val(blood_group);
            $('input[name="last_donation_date"]').val(lastDonationDate);
            $('#donor_form').attr('action',url);
          }
        }
      }else if(tag && (actionType =='delete')){
        let element=$(tag);
        if(element){
          let url=element.data('url');
          if(url){
            $('.modal-header').html('Delete Blood Donor');
            $('.main-fields').hide();
            $('.delete-msg').show();
            $('#donor_form')[0].reset();
            $('#donor_form').attr('action',url);
          }
        }
      }

    } else { // add
      modal.classList.add('hidden');
      modal.classList.remove('flex');

      $('.modal-header').html('Add Blood Donor');
      $('.main-fields').show();
      $('.delete-msg').hide();
      $('#donor_form').attr('action',$('#donor_form').data('action'));
      $('#donor_form')[0].reset();
    }
  }

  let valid_form = ['donor_form'];
  var formId = $('.form-class').prop('id');

  function validateForm() {
    if (!valid_form.includes(formId)) {
      console.log('Form content not found!');
      return 0;
    }


    var form = $("#" + formId);

    $.validator.addMethod("genderRequired", function(value, element) {
      return $('input[name="gender"]:checked').length === 1;
    }, "Please select a gender.");

    $.validator.addMethod("validBloodGroup", function(value, element) {
      return value.length <= 100;
    }, "Blood group must not exceed 100 characters.");

    $.validator.addMethod("validDate", function(value, element) {
      var inputDate = new Date(value);
      var today = new Date();
      return inputDate <= today;
    }, "Date cannot be in the future.");

    form.validate({
      rules: {
        name: {
          required: true,
          maxlength: 255
        },
        age: {
          required: true,
          number: true,
          min: 18,
          max: 100
        },
        blood_group: {
          required: true,
          validBloodGroup: true
        },
        last_donation_date: {
          required: true,
          date: true,
          validDate: true
        },
        gender: {
          genderRequired: true
        }
      },
      messages: {
        name: {
          required: "Please enter the donor's name.",
          maxlength: "Name must not exceed 255 characters."
        },
        age: {
          required: "Please enter the donor's age.",
          number: "Age must be a number.",
          min: "Minimum age is 18.",
          max: "Maximum age is 100."
        },
        blood_group: {
          required: "Please enter the blood group.",
          validBloodGroup: "Blood group must not exceed 100 characters."
        },
        last_donation_date: {
          required: "Please select the last donation date.",
          date: "Please enter a valid date.",
          validDate: "Last donation date cannot be in the future."
        },
        gender: {
          genderRequired: "Please select the gender."
        }
      },
      errorPlacement: function (error, element) {
        error.addClass('text-danger text-sm');
        if (element.prop("type") === "checkbox") {
          console.log('checked',element.parent("label").parent('div'));
                error.insertAfter(element.parent("label").parent('div'));
        }else{
          error.insertAfter(element);
        }
      },
      errorElement: 'label',
      submitHandler: function (form) {
        form.submit();
      }
    });
  }

  validateForm();
</script>

@endpush




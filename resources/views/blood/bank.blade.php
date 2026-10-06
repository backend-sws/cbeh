@extends('layouts.admin')

@section('title', 'Blood Bank List')

@section('content')


<body class="bg-gray-50 p-6 font-sans ">

 <div class="flex justify-between items-center mb-4 ">
            <h1 class="text-2xl font-bold text-gray-800">Blood Group</h1>
            <div class="flex space-x-2">
                <button class="flex items-center customborder border-main-color space-x-2 px-4 py-2 bg-pink-200 text-gray-700 rounded-md hover:bg-gray-300">
                    <i class="ph ph-funnel"></i> <!-- Filter Icon -->
                </button>
                 <button onclick="toggleBloodGroupModal(true)" class="text-main-color bg-pink-200 customborder border-main-color px-6 py-2 rounded-lg flex items-center" onclick="window.location.href='/newipdpatient'">
                            New Blood Group 
                        </button>
                      
                {{-- <a href="#" class="px-4 py-2 bg-pink-300 text-white font-semibold rounded-md hover:bg-pink-600">
                    New IPD Patients
                </a> --}}
            </div>
        </div>
 <div class="overflow-x-auto mt-6">
  <!-- msg -->
  <x-alert type="success" :message="session('success')" />
    <x-alert type="error" :message="session('error')" :errorsop="session('errorsop')" />

  <table class="min-w-full text-sm text-left border-collapse">
    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
      <tr>
        <th class="px-6 py-4">Blood Group </th>
        <th class="px-6 py-4">Reminds Bags </th>
        <th class="px-6 py-4">Action</th>
      </tr>
    </thead>
    <tbody class="text-gray-700">
    @foreach($bloodBank as $bank)
      <!-- Row -->
      <tr class="bg-gray-50 border-b">
        <td class="px-6 py-4">
          <span class="bg-green-200 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">{{$bank->blood_group}}</span>
        </td>
        <td class="px-6 py-4">
          <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded border border-blue-400">{{$bank->remained_bags}}</span>
        </td>
        <td class="px-6 py-4 flex space-x-2">
          <button onclick="toggleBloodGroupModal(true,this,'edit')" data-url="{{route('admin.blood.bank_update',$bank->id)}}" data-blood-group="{{$bank->blood_group}}" data-remained-bags="{{$bank->remained_bags}}" class="text-gray-700 dark:text-gray-500 hover:text-green-500 rounded-md p-2 customborder border-main-color">
              <i class="fa-solid fa-pen-to-square"></i>
          </button>
          <button onclick="toggleBloodGroupModal(true,this,'delete')" data-url="{{route('admin.blood.bank_delete',$bank->id)}}" class="text-gray-700 dark:text-gray-500 hover:text-red-500 rounded-md p-2 customborder border-main-color">
              <i class="fa-solid fa-trash"></i>
          </button>
        </td>
      </tr>
      @endforeach 
    </tbody>
  </table>
</div>

   <!-- Pagination -->
  
   @include('partials.pagination',['users' => $bloodBank])
        <br>
</body>


<!-- Modal -->
<div id="bloodGroupModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
  <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-md relative">
    
    <!-- Header -->
    <div class="flex justify-between items-center border-b pb-3 mb-4">
      <h2 class="text-lg font-semibold modal-header">New Blood Group</h2>
      <button onclick="toggleBloodGroupModal(false)" class="text-2xl text-gray-500 hover:text-black">&times;</button>
    </div>

    <!-- Form -->
    <form class="space-y-4 form-class" id="blood_bank" method="post"  action="{{route('admin.blood.bank_save')}}" data-action="{{route('admin.blood.bank_save')}}">
      @csrf 
      <div class="main-fields">
        <!-- Blood Group -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Blood Group: <span class="text-red-500">*</span></label>
          <input type="text" name="blood_group" id="blood_group" value="{{ old('blood_group') }}" placeholder="Enter here..." class="w-full mt-1 px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-purple-500"/>
        </div>

        <!-- Remained Bags -->
        <div>
          <label class="block text-sm font-medium text-gray-700">Remained Bags: <span class="text-red-500">*</span></label>
          <select name="remained_bags" id="remained_bags" class="w-full mt-1 px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-purple-500">
            <option disabled selected>Enter here...</option>
            <option value="10">10</option>
            <option value="20">20</option>
            <option value="30">30</option>
          </select>
        </div>
      </div>
      <div class="mt-2 delete-msg" style="display:none;">
          Are you Sure you want to Delete this Record ?
      </div>

      <!-- Actions -->
      <div class="flex justify-end space-x-3 pt-4">
        <button type="submit" class="bg-purple-700 text-white px-6 py-2 rounded hover:bg-purple-800">Save</button>
        <button type="button" onclick="toggleBloodGroupModal(false)" class="bg-gray-300 text-black px-6 py-2 rounded hover:bg-gray-400">Cancel</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script>
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
          let bags=element.data('remained-bags');
          let blood_group=element.data('blood-group');
          if(url && blood_group && bags){
            $('.modal-header').html('Edit Blood Group');
            $('.main-fields').show();
            $('.delete-msg').hide();
            $('#blood_group').val(blood_group);
            $('#remained_bags').val(bags);
            $('#blood_bank').attr('action',url);
          }
        }
      }else if(tag && (actionType =='delete')){
        let element=$(tag);
        if(element){
          let url=element.data('url');
          if(url){
            $('.modal-header').html('Delete Blood Group ');
            $('.main-fields').hide();
            $('.delete-msg').show();
            $('#blood_bank')[0].reset();
            $('#blood_bank').attr('action',url);
          }
        }
      }

    } else { // add
      console.log('hide',$('.delete-msg'));
      modal.classList.add('hidden');
      modal.classList.remove('flex');

      $('.modal-header').html('Add Blood Group');
      $('.main-fields').show();
      $('.delete-msg').hide();
      $('#blood_bank').attr('action',$('#blood_bank').data('action'));
      $('#blood_bank')[0].reset();
    }
  }
  let valid_form = ['blood_bank'];
  var formId = $('.form-class').prop('id');

  function validateForm() {
    if (!valid_form.includes(formId)) {
      console.log('Form content not found!');
      return 0;
    }

    var form = $("#" + formId);

    form.validate({
      rules: {
        blood_group: {
          required: true
        },
        remained_bags: {
          required: true,
          number: true,
          min: 1,
        }
      },
      messages: {
        blood_group: {
          required: "Please enter the blood group"
        },
        remained_bags: {
          required: "Please enter the bags",
          number: "Bags must be a number",
          min: "Bags must be at least 1"
        },
      },
      errorPlacement: function (error, element) {
        error.addClass('text-danger text-sm');
        error.insertAfter(element);
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




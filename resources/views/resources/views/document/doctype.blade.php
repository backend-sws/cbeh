@extends('layouts.admin')

@section('title', 'Document Type List')

@section('content')


<body class="bg-gray-50 p-6 font-sans ">

 <div class="flex justify-between items-center mb-4 ">
            <h1 class="text-2xl font-bold text-gray-800">Document Types</h1>
            <div class="flex space-x-2">
                <button class="flex items-center customborder border-main-color space-x-2 px-4 py-2 bg-pink-200 text-gray-700 rounded-md hover:bg-gray-300">
                    <i class="ph ph-funnel"></i> <!-- Filter Icon -->
                </button>
                 <button onclick="toggleDoctypeModal(true)" class="text-main-color bg-pink-200 customborder border-main-color px-6 py-2 rounded-lg flex items-center" onclick="window.location.href='/newipdpatient'">
                            New Document Type
                        </button>
            </div>
        </div>

<body class="bg-gray-100 p-8">

  <div class="bg-white rounded-lg shadow-md overflow-x-auto">
     <!-- msg -->
     <x-alert type="success" :message="session('success')" />
      <x-alert type="error" :message="session('error')" :errorsop="session('errorsop')" />

    <table class="min-w-full text-sm text-left">
      <thead class="border-b">
        <tr class="text-gray-500 uppercase text-xs tracking-wider">
          <th class="px-6 py-4">DOCUMENTS TYPE</th>
          <th class="px-6 py-4"></th>
          <th class="px-6 py-4">ACTION</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
      @foreach($document_types as $document_type)
        <!-- Row 1 -->
        <tr class="bg-gray-100 hover:bg-gray-50">
          <td class="px-6 py-4">{{$document_type->doc_type}}</td>
          <td class="px-6 py-4"></td>
          <td class="px-6 py-4 flex space-x-2">
             <button onclick="toggleDoctypeModal(true,this,'edit')" data-url="{{route('admin.document.document_type_update',$document_type->id)}}" data-doc-type="{{$document_type->doc_type}}" data-formate="{{$document_type->formate}}" class="text-gray-700 dark:text-gray-500 hover:text-green-500 rounded-md p-2 customborder border-main-color">
                  <i class="fa-solid fa-pen-to-square"></i>
              </button>
              <button onclick="toggleDoctypeModal(true,this,'delete')" data-url="{{route('admin.document.document_type_delete',$document_type->id)}}" class="text-gray-700 dark:text-gray-500 hover:text-red-500 rounded-md p-2 customborder border-main-color">
                  <i class="fa-solid fa-trash"></i>
              </button>
          </td>
        </tr>
        @endforeach   
      </tbody>
    </table>
  </div>

  <!-- Font Awesome for icons -->
  
</body>


   <!-- Pagination -->
   @include('partials.pagination',['users' => $document_types])
        <br>
</body>


<!-- Modal -->
<div id="bloodGroupModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
  <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-md relative">
    
    <!-- Header -->
    <div class="flex justify-between items-center border-b pb-3 mb-4">
      <h2 class="text-lg font-semibold modal-header">New Document Type</h2>
      <button onclick="toggleDoctypeModal(false)" class="text-2xl text-gray-500 hover:text-black">&times;</button>
    </div>

    <!-- Form -->
     <form class="space-y-6 form-class" id="document_type_form" method="post"  action="{{route('admin.document.document_type_save')}}" data-action="{{route('admin.document.document_type_save')}}">
            @csrf
        <div class="main-fields">
          <!-- Document Type -->
          <div>
            <label class="block text-gray-700 text-sm mb-2">Document Type: <span class="text-red-500">*</span></label>
            <select name="doc_type" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
              <option selected disabled>Select Document Type</option>
              <option value="1">Doc type 1</option>
              <option value="2">Doc type 2</option>
            </select>
          </div>

          <!-- Format -->
          <div>
            <label class="block text-gray-700 text-sm mb-2">Format: <span class="text-red-500">*</span></label>
            <select name="formate" class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
              <option selected disabled>Select Formate</option>
              <option value="1">Formate 1</option>
              <option value="2">Formate 2</option>
            </select>
          </div>

        </div>
        <div class="mt-2 delete-msg" style="display:none;">
            Are you Sure you want to Delete this Record ?
        </div>
        <!-- Buttons -->
        <div class="flex justify-end space-x-4 pt-4">
          <button type="submit" class="bg-purple-700 hover:bg-purple-800 text-white font-semibold px-6 py-2 rounded-md">
            Save
          </button>
          <button type="button" onclick="toggleDoctypeModal(false)" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-md">
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
  function toggleDoctypeModal(show,tag=null,actionType=null) {
    const modal = document.getElementById('bloodGroupModal');
    if (show) { // edit & delete
      modal.classList.remove('hidden');
      modal.classList.add('flex');

      if(tag && (actionType =='edit')){ // edit
        // console.log(tag,$(tag).data('bags'));
        let element=$(tag);
        if(element){
          let url=element.data('url');
          let formate=element.data('formate');
          let doctype=element.data('doc-type');
          if(url){
            $('.modal-header').html('Edit Document Type');
            $('.main-fields').show();
            $('.delete-msg').hide();
            $("select[name=formate]").val(formate);
            $("select[name=doc_type]").val(doctype);
            $('#document_type_form').attr('action',url);
          }
        }
      }else if(tag && (actionType =='delete')){
        let element=$(tag);
        if(element){
          let url=element.data('url');
          if(url){
            $('.modal-header').html('Delete Document Type');
            $('.main-fields').hide();
            $('.delete-msg').show();
            $('#document_type_form')[0].reset();
            $('#document_type_form').attr('action',url);
          }
        }
      }

    } else { // add
      console.log('hide',$('.delete-msg'));
      modal.classList.add('hidden');
      modal.classList.remove('flex');

      $('.modal-header').html('Add Document Type');
      $('.main-fields').show();
      $('.delete-msg').hide();
      $('#document_type_form').attr('action',$('#document_type_form').data('action'));
      $('#document_type_form')[0].reset();
    }
  }
  let valid_form = ['document_type_form'];
  var formId = $('.form-class').prop('id');

  function validateForm() {
    if (!valid_form.includes(formId)) {
      console.log('Form content not found!');
      return 0;
    }

    var form = $("#" + formId);

    form.validate({
      rules: {
        formate: {
          required: true
        },
        doc_type: {
          required: true,
        },
      },
      messages: {
        formate: {
            required: "Please enter the document formate."
        },
        doc_type: {
            required: "Please select the document type."
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








@extends('layouts.admin')

@section('title', 'Documents List')

@section('content')


<body class="bg-gray-50 p-6 font-sans ">

    <div class="flex justify-between items-center mb-4 ">
        <h1 class="text-2xl font-bold text-gray-800">Documents</h1>
        <div class="flex space-x-2">
            <button class="flex items-center customborder border-main-color space-x-2 px-4 py-2 bg-pink-200 text-gray-700 rounded-md hover:bg-gray-300">
                <i class="ph ph-funnel"></i> <!-- Filter Icon -->
            </button>
            <button onclick="toggleDocModal(true)" class="text-main-color bg-pink-200 customborder border-main-color px-6 py-2 rounded-lg flex items-center" onclick="window.location.href='/newipdpatient'">
                New Documents
            </button>
        </div>
    </div>
    <body class="bg-gray-100 p-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <!-- msg -->
            <x-alert type="success" :message="session('success')" />
                <x-alert type="error" :message="session('error')" :errorsop="session('errorsop')" />

            <table class="min-w-full text-sm text-left">
                <thead>
                    <tr class="border-b">
                        <th class="px-6 py-3 font-medium text-gray-500">FILE NAME</th>
                        <th class="px-6 py-3 font-medium text-gray-500">DOCUMENTS TYPE</th>
                        <th class="px-6 py-3 font-medium text-gray-500">PATIENT</th>
                        <th class="px-6 py-3 font-medium text-gray-500">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                @foreach($documents as $document)
                    <!-- Row 1 -->
                    <tr class="hover:bg-gray-50">
                        <td class="flex items-center px-6 py-4 space-x-2">
                            <img src="https://via.placeholder.com/30" alt="file icon" class="w-8 h-8">
                            <span>{{$document->attachment}}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-100 text-blue-600 text-xs font-semibold px-3 py-1 rounded-full">{{$document->doc_type}}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{$document->patient}}</div>
                            <div class="text-gray-500 text-xs">arvind2024@gmail.com</div>
                        </td>
                        <td class="px-6 py-4 flex space-x-2">
                             <button  class="text-gray-700 dark:text-gray-500 hover:text-green-500 rounded-md p-2 customborder border-main-color">
                                <i class="fa-solid fa-download"></i>
                            </button>
                            <button onclick="toggleDocModal(true,this,'edit')" data-url="{{route('admin.document.document_update',$document->id)}}" data-title="{{$document->title}}" data-doc-type="{{$document->doc_type}}" data-patient="{{$document->patient}}" data-attachment="{{$document->attachment}}" data-notes="{{$document->notes}}" class="text-gray-700 dark:text-gray-500 hover:text-green-500 rounded-md p-2 customborder border-main-color">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button onclick="toggleDocModal(true,this,'delete')" data-url="{{route('admin.document.document_delete',$document->id)}}" class="text-gray-700 dark:text-gray-500 hover:text-red-500 rounded-md p-2 customborder border-main-color">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach    
                </tbody>
            </table>
        </div>
    </body>
    <!-- Pagination -->
    @include('partials.pagination',['users' => $documents])
    <br>
</body>


<!-- Modal -->
<div id="bloodGroupModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-md relative">

        <!-- Header -->
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h2 class="text-lg font-semibold modal-header">New Document</h2>
            <button onclick="toggleDocModal(false)" class="text-2xl text-gray-500 hover:text-black">&times;</button>
        </div>

        <!-- Form -->
        <form class="space-y-6 form-class" id="document_form" method="post"  action="{{route('admin.document.document_save')}}" data-action="{{route('admin.document.document_save')}}">
            @csrf
            <div class="main-fields">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div>
                        <label class="block text-gray-700 text-sm mb-2">Title: <span class="text-red-500">*</span></label>
                        <input type="text" name="title" placeholder="Enter here..." class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <!-- Document Type -->
                    <div>
                        <label class="block text-gray-700 text-sm mb-2">Document Type: <span class="text-red-500">*</span></label>
                        <input type="text" name="doc_type" placeholder="Enter here..." class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <!-- Patient -->
                    <div>
                        <label class="block text-gray-700 text-sm mb-2">Patient: <span class="text-red-500">*</span></label>
                        <input type="text" name="patient" placeholder="Enter here..." class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <!-- Attachment -->
                    <div>
                        <label class="block text-gray-700 text-sm mb-2">Attachment: <span class="text-red-500">*</span></label>
                        <input type="text" name="attachment" placeholder="Enter here..." class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    </div>

                    <!-- Notes -->
                    <div>
                    <label class="block text-gray-700 text-sm mb-2">Notes: <span class="text-red-500">*</span></label>
                    <textarea rows="4" name="notes" placeholder="Enter here..." class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                    </div>

            </div>
            <div class="mt-2 delete-msg" style="display:none;">
                Are you Sure you want to Delete this Record ?
            </div>
            <!-- Buttons -->
            <div class="flex justify-end space-x-4">
            <button type="submit" class="bg-purple-700 hover:bg-purple-800 text-white font-semibold px-6 py-2 rounded-md">
                Save
            </button>
            <button type="button" onclick="toggleDocModal(false)" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded-md">
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
  function toggleDocModal(show,tag=null,actionType=null) {
    const modal = document.getElementById('bloodGroupModal');
    if (show) { // edit & delete
      modal.classList.remove('hidden');
      modal.classList.add('flex');

      if(tag && (actionType =='edit')){ // edit
        // console.log(tag,$(tag).data('bags'));
        let element=$(tag);
        if(element){
          let url=element.data('url');
          let title=element.data('title');
          let patient=element.data('patient');
          let doctype=element.data('doc-type');
          let attachment=element.data('attachment');
          let notes=element.data('notes');
          if(url){
            $('.modal-header').html('Edit Document');
            $('.main-fields').show();
            $('.delete-msg').hide();
            $("input[name=title]").val(title);
            $("input[name=patient]").val(patient);
            $("input[name=attachment]").val(attachment);
            $("input[name=doc_type]").val(doctype);
            $("textarea[name=notes]").val(notes);
            $('#document_form').attr('action',url);
          }
        }
      }else if(tag && (actionType =='delete')){
        let element=$(tag);
        if(element){
          let url=element.data('url');
          if(url){
            $('.modal-header').html('Delete Document');
            $('.main-fields').hide();
            $('.delete-msg').show();
            $('#document_form')[0].reset();
            $('#document_form').attr('action',url);
          }
        }
      }

    } else { // add
      console.log('hide',$('.delete-msg'));
      modal.classList.add('hidden');
      modal.classList.remove('flex');

      $('.modal-header').html('Add Document');
      $('.main-fields').show();
      $('.delete-msg').hide();
      $('#document_form').attr('action',$('#document_form').data('action'));
      $('#document_form')[0].reset();
    }
  }
  let valid_form = ['document_form'];
  var formId = $('.form-class').prop('id');

  function validateForm() {
    if (!valid_form.includes(formId)) {
      console.log('Form content not found!');
      return 0;
    }

    var form = $("#" + formId);

    form.validate({
      rules: {
        title: {
          required: true
        },
        patient: {
          required: true,
        },
        doc_type: {
          required: true,
        },
        attachment: {
          required: true,
        },
        notes: {
          required: true,
        }
      },
      messages: {
        title: {
            required: "Please enter the document title."
        },
        patient: {
            required: "Please select a patient."
        },
        doc_type: {
            required: "Please select the document type."
        },
        attachment: {
            required: "Please upload a file."
        },
        notes: {
            required: "Please enter some notes."
        }
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





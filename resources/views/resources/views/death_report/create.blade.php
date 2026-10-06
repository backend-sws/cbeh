@extends('layouts.admin')
@section('title', 'Generate Death Certificate')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single { height: 42px; border: 1px solid #d1d5db; border-radius: 0.5rem; display: flex; align-items: center; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { top: 8px; }
    </style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border p-6 mt-6">
    <h2 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Generate Death Certificate</h2>

    <form action="{{ route('admin.death_report.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 mb-1">Select IPD Patient (Optional)</label>
                <select name="ipd_patient_id" id="ipdSelect" class="w-full">
                    <option value="">-- Manual Entry --</option>
                    @foreach ($patients as $p)
                        <option value="{{ $p->id }}" 
                            data-name="{{ $p->patient->name }}"
                            data-gender="{{ ucfirst($p->patient->gender) }}"
                            data-address="{{ $p->patient->address }}">
                            {{ $p->patient->name }} (IPD: {{ $p->ipd_no }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Patient's Name *</label>
                <input type="text" name="patient_name" id="patientName" required class="w-full border rounded-lg p-2.5">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Gender *</label>
                <select name="gender" id="gender" class="w-full border rounded-lg p-2.5">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Date of Death *</label>
                <input type="date" name="death_date" required value="{{ date('Y-m-d') }}" class="w-full border rounded-lg p-2.5">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Time of Death *</label>
                <input type="time" name="death_time" required value="{{ date('H:i') }}" class="w-full border rounded-lg p-2.5">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 mb-1">Cause of Death *</label>
                <input type="text" name="cause_of_death" placeholder="e.g. Cardiac Arrest" required class="w-full border rounded-lg p-2.5">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 mb-1">Address</label>
                <textarea name="address" id="address" rows="2" class="w-full border rounded-lg p-2.5"></textarea>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.death_report.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-6 py-2 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700">Generate Certificate</button>
        </div>
    </form>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $('#ipdSelect').select2({ placeholder: "Search Patient...", allowClear: true, width: '100%' });

    $('#ipdSelect').on('select2:select', function (e) {
        var data = e.params.data.element;
        if (data) {
            $('#patientName').val($(data).data('name'));
            $('#gender').val($(data).data('gender'));
            $('#address').val($(data).data('address'));
        }
    });
</script>
@endpush
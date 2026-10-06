@extends('layouts.admin')

@section('title', 'Patient')

@section('content')
<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto  rounded-lg p-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-semibold">New </h2>
            <a href="{{ url()->previous() }}">
            <button class="text-main-color customborder px-4 py-2 rounded-lg hover:bg-main-color">&larr; Back</button>
            </a>
        </div>
        <div class="msg-container"></div>
        <form class="p-6 bg-white shadow-md rounded-lg form-class" id="add_billing" method="post" action="{{route('admin.billing.add_bill_save')}}">
         @csrf    
        <div class="grid grid-cols-2 gap-4">
                <div>
                @php 
                    $admission_options=[
                        '1' => 'Admission 1',
                        '2' => 'Admission 2',
                        '3' => 'Admission 3',
                    ];
                    @endphp 
                    <label class="block text-sm font-medium">Admission ID: *</label>
                    <select class="w-full p-2 border rounded" name="admission_id">
                        <option selected disabled>Select Admission id</option>
                        @foreach($admission_options as $value =>$name)
                        <option value="{{$value}}">{{$name}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Bill Date: *</label>
                    <input type="date" name="bill_date" class="w-full p-2 border rounded" />
                </div>
                <div>
                @php 
                    $patient_options=[
                        '1' => 'Patient 1',
                        '2' => 'Patient 2',
                        '3' => 'Patient 3',
                    ];
                    @endphp
                    <label class="block text-sm font-medium">Patient: *</label>
                    <select class="w-full p-2 border rounded" name="patient_id">
                        <option selected disabled>Select Patient</option>
                        @foreach($patient_options as $value =>$name)
                        <option value="{{$value}}">{{$name}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Patient Email: *</label>
                    <input type="email" name="patient_email" class="w-full p-2 border rounded" placeholder="Enter here..." />
                </div>
                <div>
                    <label class="block text-sm font-medium">Patient DOB: *</label>
                    <input type="date" name="patient_dob" class="w-full p-2 border rounded" />
                </div>
                <div>
                @php 
                    $doctor_options=[
                        '1' => 'Doctor 1',
                        '2' => 'Doctor 2',
                        '3' => 'Doctor 3',
                    ];
                    @endphp
                    <label class="block text-sm font-medium">Doctor: *</label>
                    <select class="w-full p-2 border rounded" name="doctor_id">
                        <option selected disabled>Select Doctor</option>
                        @foreach($doctor_options as $value =>$name)
                        <option value="{{$value}}">{{$name}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Admission Date: *</label>
                    <input type="date" name="admission_date" class="w-full p-2 border rounded" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Discharge Date: *</label>
                    <input type="date" name="discharge_date" class="w-full p-2 border rounded" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Package Name: *</label>
                    <input type="text" name="package_name" class="w-full p-2 border rounded" placeholder="Enter here..." />
                </div>
                <div>
                @php 
                    $insurance_options=[
                        '1' => 'Insurance 1',
                        '2' => 'Insurance 2',
                        '3' => 'Insurance 3',
                    ];
                    @endphp
                    <label class="block text-sm font-medium">Insurance Name: *</label>
                    <select class="w-full p-2 border rounded" name="insurance_id">
                        <option selected disabled>Select Insurance</option>
                        @foreach($insurance_options as $value =>$name)
                        <option value="{{$value}}">{{$name}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Total Days: *</label>
                    <input type="number" name="total_days" class="w-full p-2 border rounded" placeholder="Enter here..." />
                </div>
                <div>
                    <label class="block text-sm font-medium">Policy No: *</label>
                    <input type="text" name="policy_no" class="w-full p-2 border rounded" placeholder="Enter here..." />
                </div>
            </div>

            <div class="mt-6 bg-gray-100 p-4 rounded">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="p-2 border">#</th>
                            <th class="p-2 border">ITEM NAME*</th>
                            <th class="p-2 border">QTY*</th>
                            <th class="p-2 border">PRICE*</th>
                            <th class="p-2 border">AMOUNT*</th>
                            <th class="p-2 border">ACTION*</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-2 border">1</td>
                            <td class="p-2 border"><input type="text" class="w-full p-1 border rounded" placeholder="Item Name" /></td>
                            <td class="p-2 border"><input type="number" class="w-full p-1 border rounded" placeholder="Qty" /></td>
                            <td class="p-2 border"><input type="text" class="w-full p-1 border rounded" placeholder="Price" /></td>
                            <td class="p-2 border">0.00</td>
                            <td class="p-2 border text-center  cursor-pointer"> <i class="fa-solid fa-trash"></i></td>
                        </tr>
                    </tbody>
                </table>
                <p class="mt-4 font-semibold">Total Amount: AR$0</p>
            </div>

            <div class="flex justify-end gap-4 mt-6">
                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded form-submit-btn">Save</button>
                <button type="button" class="bg-gray-300 px-4 py-2 rounded cancle-btn">Cancel</button>
            </div>
        </form>


     
    </div>
</body>
@endsection

@push('js')
<script src="{{asset('assets/js/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/js/bill.js')}}"></script>
@endpush 
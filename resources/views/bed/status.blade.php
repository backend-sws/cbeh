@extends('layouts.admin')

@section('title', 'Beds')
<style>
    .tooltip-container {
        position: absolute;
       
    }
    .tooltip-text {
        visibility: hidden;
        width: 300px;
        background-color: white;
        color: #333;
        text-align: left;
        border-radius: 4px;
        padding: 10px;
        position: absolute;
        z-index: 100;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        opacity: 0;
        transition: opacity 1s;

    }

    .details:hover .tooltip-text {
        visibility: visible;
        opacity: 1;
       
    }
    .details:hover{
        cursor: pointer;
    }
    .tooltip-text::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: white transparent transparent transparent;
       
    }
   

</style>
@section('content')
<body class="bg-gray-100 p-6">
  <div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="card bg-white p-6 flex justify-between items-center mb-6">
      <h1 class="text-2xl font-semibold text-gray-800">Bed Management</h1>
      <button class="bg-white border border-pink-400 text-pink-500 px-4 py-1 rounded hover:bg-pink-50 transition">Back</button>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Total Beds -->
        <div class="bg-blue-500 text-white rounded-lg p-4 shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium">Total Beds</p>
                    <h2 class="text-2xl font-bold">{{ $totalBeds }}</h2>
                </div>
                <i class="fas fa-bed text-white opacity-50 text-3xl"></i>
            </div>
        </div>
        
        <!-- Total Allotted -->
        <div class="bg-red-500 text-white rounded-lg p-4 shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium">Total Allotted</p>
                    <h2 class="text-2xl font-bold">{{ $totalAllotted }}</h2>
                </div>
                <i class="fas fa-procedures text-white opacity-50 text-3xl"></i>
            </div>
        </div>
        
        <!-- Allotted Today -->
        <div class="bg-green-500 text-white rounded-lg p-4 shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium">Allotted Today</p>
                    <h2 class="text-2xl font-bold">{{ $allottedToday }}</h2>
                </div>
                <i class="fas fa-user-plus text-white opacity-50 text-3xl"></i>
            </div>
        </div>
        
        <!-- Discharged Today -->
        <div class="bg-cyan-500 text-white rounded-lg p-4 shadow">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium">Discharged Today</p>
                    <h2 class="text-2xl font-bold">{{ $dischargedToday }}</h2>
                </div>
                <i class="fas fa-user-minus text-white opacity-50 text-3xl"></i>
            </div>
        </div>
    </div>

    {{-- Bed Status by Type --}}
    @foreach($bedTypes as $type)
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-700">{{ $type->name }} Beds ({{ $type->beds->count() }})</h2>
            <div class="flex space-x-2 details" style="position: relative;">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <span class="w-2 h-2 mr-1 rounded-full bg-green-500"></span> 
                    Available: {{ $type->beds->where('is_available', true)->count() }}
                </span>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <span class="w-2 h-2 mr-1 rounded-full bg-red-500"></span> 
                    Occupied: {{ $type->beds->where('is_available', false)->count() }}
                </span>
            </div>
        </div>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 ">
            @foreach($type->beds as $bed)
                <div class="border rounded-lg overflow-hidden transition-all hover:shadow-md 
                    {{ $bed->is_available ? 'border-green-300 bg-green-50' : 'border-red-300 bg-red-50' }}">
                 <div class="p-3 text-center details" >
                        <div class="{{ $bed->is_available ? 'text-green-500' : 'text-red-500' }} bed-icon">
                            <i class="fas fa-bed text-3xl mb-2"></i>
                        </div>
                        <h3 class="font-medium text-gray-800">{{ $bed->name }}</h3>
                        
                        <span class="inline-block mt-1 px-2 py-1 text-xs font-semibold rounded 
                            {{ $bed->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $bed->is_available ? 'Available' : 'Occupied' }}
                        </span>
                        
                      @if(!$bed->is_available && isset($bed->assigned_patient))
                            @php
                                $admissionDate = $bed->bed_assigns->first()->assigned_date
                                    ? \Carbon\Carbon::parse($bed->bed_assigns->first()->assigned_date)->format('M d, Y')
                                    : 'Unknown';
                            @endphp
                            <div class="mt-2 pt-2 border-t border-gray-200 tooltip-container">
                              
                                <div class="tooltip-text">
                                    <p class="font-medium mb-1">Details</p>
                                     <p><strong>Bed Name:</strong> {{ $bed->name }}</p>
                                    <p><strong>Patient Name:</strong> {{ $bed->bed_assigns->first()->patient->name }}</p>
                                    <p><strong>Phone:</strong> {{ $bed->bed_assigns->first()->patient->phone }}</p>
                                    <p><strong>Admitted:</strong> {{ $admissionDate }}</p>
                                    <p><strong>Gender:</strong> {{ $bed->bed_assigns->first()->patient->gender }}</p>
                                </div>
                            </div>
                        @endif
                   

                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endforeach
  </div>
</body>
<script>
    function assignBed(bedId, availability) {
        console.log("check availability", availability)
        if (availability) {
            // Redirect to the bed assignment page with the selected bed ID
            window.location.href = `/admin/bed/bed-assign/${bedId}`;
        } else {
            alert('This bed is not available for assignment.');
        }
    }
</script>
@endsection
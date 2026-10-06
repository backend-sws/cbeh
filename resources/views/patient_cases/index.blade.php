@extends('layouts.admin')

@section('title', 'Blood')

@section('content')


<body class="bg-gray-50 p-6 font-sans ">

    <div class="flex justify-between items-center mb-4 ">
        <h1 class="text-2xl font-bold text-gray-800">Case</h1>
        <div class="flex space-x-2">
            <button class="flex items-center customborder border-main-color space-x-2 px-4 py-2 bg-pink-200 text-gray-700 rounded-md hover:bg-gray-300">
                <i class="ph ph-funnel"></i> <!-- Filter Icon -->
            </button>
            <button class="text-main-color bg-pink-200 customborder border-main-color px-6 py-2 rounded-lg flex items-center" onclick="window.location.href='patient-cases/create'">
                New Case
            </button>

        </div>
    </div>
    <div class="bg-white shadow overflow-x-auto rounded-lg">

       <div class="overflow-x-auto p-4">
    <table class="min-w-full bg-white border border-gray-200 rounded-md text-sm">
        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
            <tr>
                <th class="px-4 py-3 text-left">CASE ID</th>
                <th class="px-4 py-3 text-left">Patient</th>
                <th class="px-4 py-3 text-left">Date</th>
                <th class="px-4 py-3 text-left">Doctors</th>
                <th class="px-4 py-3 text-left">Fee</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Action</th>
            </tr>
        </thead>
        <tbody>
          
            <!-- Row 2 -->
            @foreach ($cases as $case)
            <tr class="border-t">
                <!-- Case ID -->
                <td class="px-4 py-3">
                    <span class="bg-pink-200 text-pink-700 font-semibold px-3 py-1 rounded-full">
                        {{ $case->case_id }}
                    </span>
                </td>

                <!-- Patient Info -->
                <td class="px-4 py-3">
                    <div class="font-medium text-gray-800">{{ $case->patients->name ?? 'N/A' }}</div>
                    <div class="text-gray-500 text-xs">{{ $case->patients->email ?? 'N/A' }}</div>
                </td>

                <!-- Date/Time -->
                <td class="px-4 py-3">
                    <div class="bg-pink-100 text-pink-600 font-semibold rounded-md px-3 py-1 inline-block text-center">
                        <div>{{ \Carbon\Carbon::parse($case->date)->format('h:i A') }}</div>
                        <div class="text-xs">{{ \Carbon\Carbon::parse($case->date)->format('jS M, Y') }}</div>
                    </div>
                </td>

                <!-- Doctor Info -->
                <td class="px-4 py-3">
                    <div class="font-medium text-gray-800">{{ $case->doctors->name ?? 'N/A' }}</div>
                    <div class="text-gray-500 text-xs">{{ $case->doctors->email ?? 'N/A' }}</div>
                </td>

                <!-- Fee -->
                <td class="px-4 py-3">
                     {{ number_format($case->fee, 2) }}
                </td>

                <!-- Status Toggle (Example using status == 1) -->
                <td class="px-4 py-3">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" {{ $case->status == 1 ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-purple-600 rounded-full peer peer-checked:bg-purple-700 relative transition-all">
                            <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full shadow-md peer-checked:translate-x-5 transition-transform"></div>
                        </div>
                    </label>
                </td>

                <!-- Action Buttons -->
                <td class="px-4 py-3 space-x-2">
                    <a href="{{ route('patient-cases.edit', $case->id) }}" class="text-black hover:text-blue-600">✏️</a>
                    <form action="{{ route('patient-cases.destroy', $case->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-black hover:text-red-600">🗑️</button>
                    </form>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>





    </div>
    <!-- Pagination -->
     @include('partials.pagination',['users' => $cases])
    <br>
</body>


@endsection
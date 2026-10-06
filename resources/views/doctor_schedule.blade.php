@extends('layouts.admin')

@section('title', 'Doctor')

@section('content')

<body class="bg-gray-200 p-2">
    <div class=" bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between mb-4">
            <button class="bg-pink-500 text-white px-4 py-2 rounded-lg">New Schedule</button>
            <button class="bg-pink-500 text-white px-4 py-2 rounded-lg">← Back</button>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block font-medium">Doctors:</label>
                <select class="w-full p-2 border rounded-lg">
                    <option>Select Doctors Name...</option>
                </select>
            </div>
            <div>
                <label class="block font-medium">Per Patient Time:</label>
                <input type="text" class="w-full p-2 border rounded-lg" placeholder="Per Patient Time">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-2">AVAILABLE ON</th>
                        <th class="border p-2">AVAILABLE TO</th>
                        <th class="border p-2">AVAILABLE FROM</th>
                        <th class="border p-2">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"] as $day)
                    <tr>
                        <td class="border p-2 text-center">{{ $day }}</td>
                        <td class="border p-2"><input type="time" class="w-full p-2 border rounded-lg"></td>
                        <td class="border p-2"><input type="time" class="w-full p-2 border rounded-lg"></td>
                        <td class="border p-2 text-center">
                            <button class="bg-white text-black-300 border-2 border-pink-500 p-2 rounded-lg hover:bg-pink-100 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256">
                                    <path fill="currentColor" d="M238 136v64a14 14 0 0 1-14 14H32a14 14 0 0 1-14-14v-64a14 14 0 0 1 14-14h48a6 6 0 0 1 0 12H32a2 2 0 0 0-2 2v64a2 2 0 0 0 2 2h192a2 2 0 0 0 2-2v-64a2 2 0 0 0-2-2h-48a6 6 0 0 1 0-12h48a14 14 0 0 1 14 14M84.24 76.24L122 38.49V128a6 6 0 0 0 12 0V38.49l37.76 37.75a6 6 0 0 0 8.48-8.48l-48-48a6 6 0 0 0-8.48 0l-48 48a6 6 0 0 0 8.48 8.48M198 168a10 10 0 1 0-10 10a10 10 0 0 0 10-10" />
                                </svg>
                            </button>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-end mt-6">
            <button class="bg-pink-500 text-white px-6 py-2 rounded-lg">Save</button>
        </div>
    </div>
</body>
@endsection

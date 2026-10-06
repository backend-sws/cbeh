@extends('layouts.admin')
@section('title', 'New User')

@section('content')

    <body class="bg-gray-50 p-6 font-sans">
        <div class="max-w-6xl mx-auto">
            <!-- Card Header -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="flex justify-between items-center border-b px-6 py-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Create New {{ $name }}</h2>
                        <p class="text-sm text-gray-600 mt-1">Fill in the details to create a new user account</p>
                    </div>
                    <a href="{{ url()->previous() }}"
                        class="flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Back
                    </a>
                </div>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                        role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-4"
                        role="alert">
                        <strong class="font-bold">Whoops!</strong>
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form Container -->
                <div class="p-6">
                    <div class="msg-container mb-6"></div>
                    <form class="form-class" id="user_add" enctype="multipart/form-data" method="post"
                        action="{{ route('admin.users.add_save', $name) }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Personal Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-800 border-b pb-2">Personal Information</h3>

                                <!-- First Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                                        placeholder="Enter full name"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm @error('name') border-red-500 @enderror"
                                        required autofocus>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Last Name -->
                                {{-- <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Last Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
                                        placeholder="Enter last name"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm @error('last_name') border-red-500 @enderror"
                                        required>
                                    @error('last_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div> --}}

                                <!-- Guardian Name -->
                                <div>
                                    <label for="guardian_name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Guardian Name
                                    </label>
                                    <input type="text" name="guardian_name" id="guardian_name"
                                        value="{{ old('guardian_name') }}" placeholder="Enter guardian name"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                </div>

                                <!-- Gender -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Gender <span class="text-red-500">*</span>
                                    </label>
                                    <div
                                        class="mt-1 space-x-6 @error('gender') border border-red-500 p-2 rounded-md @enderror">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="gender" value="Male"
                                                @if (old('gender') == 'Male') checked @endif
                                                class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300">
                                            <span class="ml-2 text-gray-700">Male</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="gender" value="Female"
                                                @if (old('gender') == 'Female') checked @endif
                                                class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300">
                                            <span class="ml-2 text-gray-700">Female</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="gender" value="Other"
                                                @if (old('gender') == 'Other') checked @endif
                                                class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300">
                                            <span class="ml-2 text-gray-700">Other</span>
                                        </label>
                                    </div>
                                    @error('gender')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Date of Birth -->
                                <div style="visibility:hidden">
                                    <label for="dob" class="block text-sm font-medium text-gray-700 mb-1">
                                        Date of Birth
                                    </label>
                                    <input type="date" name="dob" id="dob" value="{{ old('dob') }}"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                        max="{{ date('Y-m-d') }}">
                                </div>

                                <!-- Age (Years & Months) -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="age" class="block text-sm font-medium text-gray-700 mb-1">
                                            Age (Years)
                                        </label>
                                        <input type="number" name="age" id="age" value="{{ old('age') }}"
                                            placeholder="Years"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label for="age_month" class="block text-sm font-medium text-gray-700 mb-1">
                                            Age (Months)
                                        </label>
                                        <input type="number" name="age_month" id="age_month" value="{{ old('age_month') }}"
                                            placeholder="Months" min="0" max="11"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-800 border-b pb-2">Contact Information</h3>

                                <!-- Email -->
                                @if ($name != 'Patient')
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                            Email <span class="text-red-500">you can leave this blank in patient
                                                details</span>
                                        </label>
                                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                                            placeholder="Enter email address"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm @error('email') border-red-500 @enderror">
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif

                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                        Phone
                                    </label>
                                    <div class="mt-1 flex rounded-md shadow-sm">
                                        <span
                                            class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                            <img src="/img/flag.png" alt="Indian Flag" class="h-4 w-4 mr-2">
                                            +91
                                        </span>
                                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                            placeholder="Enter phone number"
                                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                            maxlength="10">
                                    </div>
                                    <div class="phone-field-error text-sm text-red-600 mt-1"></div>
                                </div>

                                @if ($name != 'Patient')
                                    <!-- Alternate Phone -->
                                    <div>
                                        <label for="alternate_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                            Alternate Phone
                                        </label>
                                        <input type="text" name="alternate_phone" id="alternate_phone"
                                            value="{{ old('alternate_phone') }}" placeholder="Enter alternate phone"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                    </div>
                                @endif
                                <!-- Address -->
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                                        Address
                                    </label>
                                    <textarea name="address" id="address" rows="2"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                        placeholder="Enter address">{{ old('address') }}</textarea>
                                </div>

                                @if($name != 'Patient')
                                <!-- Alternate Address -->
                                <div>
                                    <label for="alternate_address" class="block text-sm font-medium text-gray-700 mb-1">
                                        Alternate Address
                                    </label>
                                    <textarea name="alternate_address" id="alternate_address" rows="2"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                        placeholder="Enter alternate address">{{ old('alternate_address') }}</textarea>
                                </div>
                                @endif
                            </div>

                            <!-- Additional Information -->
                            <div class="space-y-4 md:col-span-2">
                                <h3 class="text-lg font-medium text-gray-800 border-b pb-2">Additional Information</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Blood Group -->
                                    <div>
                                        <label for="blood_group" class="block text-sm font-medium text-gray-700 mb-1">
                                            Blood Group
                                        </label>
                                        <select name="blood_group" id="blood_group"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                            <option value="">Select blood group</option>
                                            @foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                                                <option value="{{ $bg }}"
                                                    @if (old('blood_group') == $bg) selected @endif>{{ $bg }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Marital Status -->
                                    <div>
                                        <label for="marital_status" class="block text-sm font-medium text-gray-700 mb-1">
                                            Marital Status
                                        </label>
                                        <select name="marital_status" id="marital_status"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                            <option value="">Select marital status</option>
                                            <option value="single" @if (old('marital_status') == 'single') selected @endif>
                                                Single</option>
                                            <option value="married" @if (old('marital_status') == 'married') selected @endif>
                                                Married</option>
                                            <option value="divorced" @if (old('marital_status') == 'divorced') selected @endif>
                                                Divorced</option>
                                            <option value="widowed" @if (old('marital_status') == 'widowed') selected @endif>
                                                Widowed</option>
                                        </select>
                                    </div>

                                    <!-- Role -->
                                    <div>
                                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                            Role
                                        </label>
                                        <input type="text" name="role" value="{{ $name }}" disabled
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-gray-100 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                            required>
                                    </div>

                                    <!-- Known Allergies -->
                                    <div>
                                        <label for="allergies" class="block text-sm font-medium text-gray-700 mb-1">
                                            Known Allergies
                                        </label>
                                        <textarea name="allergies" id="allergies" rows="2"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                            placeholder="List any known allergies">{{ old('allergies') }}</textarea>
                                    </div>

                                    <!-- Remarks -->
                                    <div>
                                        <label for="remarks" class="block text-sm font-medium text-gray-700 mb-1">
                                            Remarks
                                        </label>
                                        <textarea name="remarks" id="remarks" rows="2"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                            placeholder="Any remarks">{{ old('remarks') }}</textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Adhar Number
                                                (Optional)</label>
                                            <input type="text" name="adhar_number"
                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3  focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                                                value="{{ old('adhar_number') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Security -->
                            <div class="space-y-4 md:col-span-2">
                                <h3 class="text-lg font-medium text-gray-800 border-b pb-2">Account Security</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Password -->
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                            Password
                                        </label>
                                        <input type="password" name="password" id="password"
                                            placeholder="Enter password (leave empty to use DOB)"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                    </div>

                                    <!-- Confirm Password -->
                                    <div>
                                        <label for="confirm_password"
                                            class="block text-sm font-medium text-gray-700 mb-1">
                                            Confirm Password
                                        </label>
                                        <input type="password" name="password_confirmation" id="confirm_password"
                                            placeholder="Confirm password"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                    </div>
                                </div>
                            </div>

                            <!-- Profile Image -->
                            <div class="space-y-4 md:col-span-2">
                                <h3 class="text-lg font-medium text-gray-800 border-b pb-2">Profile Image</h3>

                                <div class="flex items-center space-x-6">
                                    <!-- Image Preview -->
                                    <div class="flex-shrink-0">
                                        <div class="w-24 h-24 border-2 border-gray-300 rounded-full overflow-hidden">
                                            <img id="profile_image" src="{{ asset('img/default_user.png') }}"
                                                alt="Profile Image" class="w-full h-full object-cover">
                                        </div>
                                    </div>

                                    <!-- Upload Button -->
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Upload Image
                                        </label>
                                        <div class="flex items-center">
                                            <label for="image" class="cursor-pointer">
                                                <span
                                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400"
                                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Choose File
                                                </span>
                                                <input type="file" id="image" name="image" class="sr-only"
                                                    accept="image/*">
                                            </label>
                                            <span class="ml-3 text-sm text-gray-500" id="file_name">No file
                                                selected</span>
                                        </div>
                                        <div class="image-field-error text-sm text-red-600 mt-1"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end mt-8">
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                Save 
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
@endsection

@push('js')
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <!--<script src="{{ asset('assets/js/user.js') }}"></script>-->
    {{-- <script>
    // Auto-calculate age from DOB
    $('#dob').on('change', function() {
        let dob = new Date(this.value);
        if (!isNaN(dob)) {
            let today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            let m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            $('#age').val(age > 0 ? age : '');
        } else {
            $('#age').val('');
        }
    });

    // Profile image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('profile_image');
        const fileName = document.getElementById('file_name');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
            fileName.textContent = file.name;
        } else {
            preview.src = "{{ asset('img/default_user.png') }}";
            fileName.textContent = 'No file selected';
        }
    });
</script> --}}
@endpush

@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg rounded-lg">
                <div class="card-header p-1">
                    <div class="flex justify-between items-center mb-1">
                        <h2 class="text-main-color text-xl font-semibold customborder border-main-color px-6 py-2 rounded-lg">Users Form</h2>
                        <button class="text-main-color customborder border-main-color px-6 py-2 rounded-lg flex items-center">
                            ← Back
                        </button>
                    </div>
                </div>

                <div class="card-body p-6 bg-white">
                    <form>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                            <!-- First Name -->
                            <div>
                                <label for="first_name" class="block text-sm font-medium mb-2">First Name:</label>
                                <input type="text" id="first_name" placeholder="Enter here" class="border p-3 rounded-md w-full">
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label for="last_name" class="block text-sm font-medium mb-2">Last Name:</label>
                                <input type="text" id="last_name" placeholder="Enter here" class="border p-3 rounded-md w-full">
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium mb-2">Email:</label>
                                <input type="email" id="email" placeholder="Enter here" class="border p-3 rounded-md w-full">
                            </div>

                            <!-- Role -->
                            <div>
                                <label for="role" class="block text-sm font-medium mb-2">Role:</label>
                                <input type="text" id="role" placeholder="Enter here" class="border p-3 rounded-md w-full">
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium mb-2">Phone:</label>
                                <div class="flex items-center">
                                    <img src="/img/flag.png" alt="Indian Flag" width="20" height="15" class="mr-2">
                                    <span class="mr-2">+91</span>
                                    <input type="text" id="phone" name="phone" placeholder="Enter here" class="border p-3 rounded-md w-full">
                                </div>
                            </div>

                            <!-- Date of Birth -->
                            <div>
                                <label for="dob" class="block text-sm font-medium mb-2">Date Of Birth:</label>
                                <input type="date" id="dob" class="border p-3 rounded-md w-full">
                            </div>

                            <!-- Gender -->
                            <div class="flex items-center space-x-6">
                                <span class="text-sm font-medium">Gender:</span>
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="gender" class="text-main-color"> <span>Male</span>
                                </label>
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="gender" class="text-gray-500"> <span>Female</span>
                                </label>
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium mb-2">Password:</label>
                                <input type="password" id="password" placeholder="Enter here" class="border p-3 rounded-md w-full">
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="confirm_password" class="block text-sm font-medium mb-2">Confirm Password:</label>
                                <input type="password" id="confirm_password" placeholder="Enter here" class="border p-3 rounded-md w-full">
                            </div>

                            <!-- Social Media Links -->
                            <div>
                                <label for="facebook_url" class="block text-sm font-medium mb-2">Facebook URL:</label>
                                <input type="url" id="facebook_url" placeholder="Enter here" class="border p-3 rounded-md w-full">
                            </div>
                            <div>
                                <label for="instagram_url" class="block text-sm font-medium mb-2">Instagram URL:</label>
                                <input type="url" id="instagram_url" placeholder="Enter here" class="border p-3 rounded-md w-full">
                            </div>
                            <div>
                                <label for="twitter_url" class="block text-sm font-medium mb-2">Twitter URL:</label>
                                <input type="url" id="twitter_url" placeholder="Enter here" class="border p-3 rounded-md w-full">
                            </div>
                            <div>
                                <label for="linkedin_url" class="block text-sm font-medium mb-2">LinkedIn URL:</label>
                                <input type="url" id="linkedin_url" placeholder="Enter here" class="border p-3 rounded-md w-full">
                            </div>

                            <!-- Profile Image Upload -->
                            <div>
                                <label for="image" class="block text-sm font-medium mb-2">Upload Image:</label>
                                <div class="w-16 h-16 border rounded-md flex items-center justify-center overflow-hidden">
                                    <img src="https://via.placeholder.com/50" alt="Profile" id="profile_image" class="w-full h-full object-cover">
                                </div>
                                <input type="file" id="image" class="hidden">
                                <label for="image" class="mt-2 inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-5 rounded">Choose File</label>
                            </div>

                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-center gap-6 mt-8">
                            <button class="bg-purple-light hover-purple-color text-black px-8 py-3 rounded-lg">Save</button>
                            <button class=" text-black bg-main-light hover-main-color px-8 py-3 rounded-lg">Send Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

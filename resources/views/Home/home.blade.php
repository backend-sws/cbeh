@extends('layouts.app')

@section('title', 'Blood')

@section('content')

<div class="bg-gray-50 p-6 font-sans">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Home</h1>
    </div>

    <div class="bg-white shadow overflow-x-auto rounded-lg">
        <div class="bg-white p-6 max-w-4xl mx-auto">
            <h1 class="text-2xl font-bold mb-6">Home</h1>

            <!-- Image Uploads -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <!-- First Image Upload -->
                <div class="text-center">
                    <label class="block mb-2">Upload Image:</label>
                    <label class="cursor-pointer">
                        <div class="w-20 h-20 mx-auto bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                            <img id="preview1" class="hidden w-full h-full object-cover" />
                            <svg id="placeholder1" class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z" />
                            </svg>
                        </div>
                        <input type="file" id="imageUpload1" accept="image/*" class="hidden" />
                    </label>
                </div>

                <!-- Second Image Upload -->
                <div class="text-center">
                    <label class="block mb-2">Upload Image:</label>
                    <div class="inline-block cursor-pointer" onclick="document.getElementById('imageUpload2').click()">
                        <div class="w-20 h-20 mx-auto bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                            <img id="preview2" class="hidden w-full h-full object-cover" />
                            <svg id="placeholder2" class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z" />
                            </svg>
                        </div>
                    </div>
                    <input type="file" id="imageUpload2" accept="image/*" class="hidden" />
                </div>
            </div>

            <!-- Form -->
            <form class="space-y-4">
                <div>
                    <label class="block font-medium mb-1">Home Page Short Description <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full border border-gray-300 rounded p-2" placeholder="Enter here..." />
                </div>

                <div>
                    <label class="block font-medium mb-1">Home Page Title <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full border border-gray-300 rounded p-2" placeholder="Enter here..." />
                </div>

                <div>
                    <label class="block font-medium mb-1">Home Page Description <span class="text-red-500">*</span></label>
                    <textarea class="w-full border border-gray-300 rounded p-2" rows="4" placeholder="Enter here..."></textarea>
                </div>

                <div>
                    <label class="block font-medium mb-1">Home Page Box Title <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full border border-gray-300 rounded p-2" placeholder="Enter here..." />
                </div>

                <div>
                    <label class="block font-medium mb-1">Home Page Box Description <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full border border-gray-300 rounded p-2" placeholder="Enter here..." />
                </div>

                <div>
                    <label class="block font-medium mb-1">Home Page Certified Box Title <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full border border-gray-300 rounded p-2" placeholder="Enter here..." />
                </div>

                <div>
                    <label class="block font-medium mb-1">Home Page Certified Box Description <span class="text-red-500">*</span></label>
                    <textarea class="w-full border border-gray-300 rounded p-2" rows="4" placeholder="Enter here..."></textarea>
                </div>

                <!-- Steps -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium mb-1">Home Page Step 1 Title <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border border-gray-300 rounded p-2" placeholder="Enter here..." />
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Home Page Step 1 Description <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border border-gray-300 rounded p-2" placeholder="Enter here..." />
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Home Page Step 2 Title <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border border-gray-300 rounded p-2" placeholder="Enter here..." />
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Home Page Step 3 Title <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full border border-gray-300 rounded p-2" placeholder="Enter here..." />
                    </div>
                </div>

                <div>
                    <label class="block font-medium mb-1">Home Page Step 4 Description <span class="text-red-500">*</span></label>
                    <textarea class="w-full border border-gray-300 rounded p-2" rows="4" placeholder="Enter here..."></textarea>
                </div>

                <div>
                    <label class="block font-medium mb-1">Home Page Step 5 Title <span class="text-red-500">*</span></label>
                    <input type="text" class="w-full border border-gray-300 rounded p-2" placeholder="Enter here..." />
                </div>

                <div>
                    <label class="block font-medium mb-1">Home Page Step 6 Description <span class="text-red-500">*</span></label>
                    <textarea class="w-full border border-gray-300 rounded p-2" rows="4" placeholder="Enter here..."></textarea>
                </div>

                <div class="flex justify-end gap-4 mt-6">
                    <button type="submit" class="bg-purple-700 text-white px-6 py-2 rounded hover:bg-purple-800">Save</button>
                    <button type="button" class="bg-gray-200 px-6 py-2 rounded hover:bg-gray-300">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    const handleImageUpload = (inputId, previewId, placeholderId) => {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        const placeholder = document.getElementById(placeholderId);

        input.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    };

    handleImageUpload('imageUpload1', 'preview1', 'placeholder1');
    handleImageUpload('imageUpload2', 'preview2', 'placeholder2');
</script>

@endsection

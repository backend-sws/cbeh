@props(['message' => null, 'id' => null, 'title' => null, 'action' => null, 'btn_name' => null, 'select_datas' => []])

<div x-data="{ open: false }">
    <!-- Trigger Button -->
    <button @click="open = true" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-brandPink">
        + {{ $btn_name }}
    </button>

    <!-- Modal -->
    <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900 bg-opacity-50">
        <div class="bg-white  rounded-lg shadow-lg w-full max-w-lg">
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-4 border-b border-gray-300 dark:border-gray-700">
                <h2 class="text-lg font-semibold dark:text-white">{{ $title }}</h2>
                <button @click="open = false" class="text-gray-600 dark:text-gray-300 hover:text-gray-800">
                    &times;
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-4">
                <form action="{{ $action }}" method="POST" id="form_{{ $id }}" enctype="multipart/form-data"
                    @submit.prevent="formSubmit($event,$el, '{{ $id }}')">
                    @csrf

                    @if (view()->exists("admin.components._{$id}_form"))
                        @include("admin.components._{$id}_form", ['select_datas' => $select_datas])
                    @else
                        <p class="text-red-500">Form not found</p>
                    @endif

                    <hr class="my-4 border-gray-300 dark:border-gray-700">

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" id="submit_{{ $id }}"
                            class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-brandPink">
                            {{ $btn_name }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

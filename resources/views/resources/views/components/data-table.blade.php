<div class="p-4">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
        <div class="flex items-center gap-4 justify-between border-b pb-4">
            <button class="w-1/5 py-2 px-4 border border-brandMagenta rounded text-brandMagenta bg-brandGray hover:bg-brandPink dark:bg-gray-700">
                + Add 
            </button>
            <input type="text" id="search" class="w-1/5 border p-2 rounded dark:bg-gray-700 dark:text-white bg-brandGray border-brandMagenta placeholder-brandMagenta focus:border-brandMagenta focus:bg-brandPink"
                placeholder="Search ...">
        </div>

        <table id="{{ $id }}" class="table-auto w-full mt-4">
            <thead>
                <tr class="bg-white dark:bg-gray-700 dark:text-white">
                    @foreach ($columns as $column)
                        <th class="border-l border-r px-4 py-2">{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <div class="pagination mt-4">
            {{ $data->links('pagination::tailwind') }}
        </div>
    </div>
</div>

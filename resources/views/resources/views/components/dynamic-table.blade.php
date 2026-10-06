@props(['table_id', 'columns', 'route'])

<section class="content">
    <div class="flex items-center gap-4 justify-between mb-4">
        <!-- Modal Form for Create Action -->
        <x-form-modal id="{{ $table_id }}" title="Create {{ ucfirst($table_id) }}" action="{{$route }}" btn_name="Create {{ ucfirst($table_id) }}" />
        
        <!-- Search Input -->
        <input type="text" id="search" class="w-1/5 border p-2 rounded 
                       bg-brandGray border-brandMagenta placeholder-brandMagenta focus:border-brandMagenta 
                       focus:bg-brandPink" placeholder="Search {{ ucfirst($table_id) }}...">
    </div>
    
    <!-- Table Container -->
    <div class="bg-white shadow-md rounded-lg ">
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse" id="{{ $table_id }}Table">
                <thead class="bg-gray-200">
                    <tr class="bg-white">
                        <!-- <th class="border border-gray-300 px-4 py-2">Sr.</th> -->
                        @foreach($columns as $column)
                            <th class="border-l border-r px-4 py-2">{{ ucfirst($column) }}</th>
                        @endforeach
                        <!-- <th class="border border-gray-300 px-4 py-2">Actions</th> -->
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</section>
@push('js')
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        initializeDataTable(`#{{ $table_id }}Table`, `{{ $route }}`, @json($columns), [[1, 'desc']], {});
    });
</script>
@endpush
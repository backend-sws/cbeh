@extends('layouts.admin')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
@endpush
@section('page_title', 'Roles & Permission')

@section('page_heading', 'Roles & Permission')

@section('top_button')

@endsection

@section('content')
<section class="p-6 bg-gray-100">
    <div class="bg-white shadow-lg rounded-lg p-6">
        <form action="{{ route('roles.assing_permission') }}" method="post">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">User Role</label>
                <select name="role_id" class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-blue-300" onchange="rolepermissiontable(this)">
                    <option hidden>Select Role</option>
                    @foreach($roles as $role)
                        @if ($role->id == 3) @continue @endif
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="overflow-x-auto" id="rolepermissiontable" style="display: none;">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="border border-gray-300 px-4 py-2 text-left">Permission</th>
                            <th class="border border-gray-300 px-4 py-2 text-center">Assign</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $parent)
                        <tr class="bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2 font-semibold">
                                {{ $parent->label ?: Str::title(Str::replace('.', ' ', $parent->name)) }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <input type="checkbox"
                                       class="form-checkbox permission-checkbox h-5 w-5 text-blue-500"
                                       name="permission_ids[]"
                                       value="{{ $parent->id }}"
                                       id="permission-{{ $parent->id }}"
                                       data-parent-id="null">
                            </td>
                        </tr>

                        @foreach ($parent->children as $child)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 pl-6">
                                ⇨ {{ Str::title(Str::replace('.', ' ', $child->name)) }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <input type="checkbox"
                                       class="form-checkbox permission-checkbox h-5 w-5 text-blue-500"
                                       name="permission_ids[]"
                                       value="{{ $child->id }}"
                                       id="permission-{{ $child->id }}"
                                       data-parent-id="{{ $parent->id }}">
                            </td>
                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 text-right">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Save
                </button>
            </div>
        </form>
    </div>
</section>
@endsection

@push('js')
<script>
    function rolepermissiontable(select) {
        let roleId = select.value;
        document.getElementById('rolepermissiontable').style.display = 'none';

        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });

        fetch('{{ route("roles.permissions") }}?role_id=' + roleId)
            .then(response => response.json())
            .then(data => {
                if (data.permissions) {
                    document.getElementById('rolepermissiontable').style.display = 'block';
                    data.permissions.forEach(permissionId => {
                        let cb = document.querySelector(`.permission-checkbox[value="${permissionId}"]`);
                        if(cb) cb.checked = true;
                    });
                }
            })
            .catch(() => alert('Failed to fetch permissions. Please try again.'));
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                let parentId = this.dataset.parentId;

                // If parent (e.g., IPD) is checked/unchecked, check/uncheck all its children
                if (parentId === 'null') {
                    document.querySelectorAll(`.permission-checkbox[data-parent-id="${this.value}"]`).forEach(childCheckbox => {
                        childCheckbox.checked = this.checked;
                    });
                } else {
                    // If a child is checked, ensure parent is checked
                    if (this.checked) {
                        let parentCheckbox = document.querySelector(`.permission-checkbox[value="${parentId}"]`);
                        if (parentCheckbox) parentCheckbox.checked = true;
                    } else {
                        // If all siblings are unchecked, uncheck parent
                        let siblings = document.querySelectorAll(`.permission-checkbox[data-parent-id="${parentId}"]`);
                        let allUnchecked = Array.from(siblings).every(cb => !cb.checked);
                        if (allUnchecked) {
                            let parentCheckbox = document.querySelector(`.permission-checkbox[value="${parentId}"]`);
                            if (parentCheckbox) parentCheckbox.checked = false;
                        }
                    }
                }
            });
        });
    });
</script>
@endpush
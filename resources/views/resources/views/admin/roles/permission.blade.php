@extends('layouts.admin')

@section('page_title', 'Manage Permission')

@section('page_heading', 'Manage Permission')


@section('content')

<section class="content">
    <div class="card">
        <div class="card-body">
            <table class="w-full border-collapse">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2 text-left">Sr</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Permission Name</th>
                        <th class="border border-gray-300 px-4 py-2 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hierarchicalPermissions as $index => $permission)
                    <tr class="odd:bg-gray-50">
                        <td class="border border-gray-300 px-4 py-2">{{ $index + 1 }}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            @if ($permission->parent_id)
                            &nbsp;&nbsp;  {{ $permission->name }} {{-- Child Indentation --}}
                            @else
                            {{ $permission->name }}
                            @endif
                        </td>
                        <td class="border border-gray-300 px-4 py-2 text-center">
                        <a href="javascript:void(0)" class="btn btn-warning btn-xs" data-url="{{ route('roles.permission.edit', $permission->id) }}"
                        onclick="edit_swal(this)" >
                            <button class="text-main-color p-2 border fa fa-edit hover-purple-color"></button></a>
                            <button class="text-main-color p-2 border fa fa-trash hover-purple-color"></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</section>
@endsection

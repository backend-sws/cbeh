@extends('layouts.admin')

@section('page_title', 'Manage Roles')

@section('page_heading', 'Manage Roles')

@section('top_button')
<button type="button" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-md" data-toggle="modal" data-target="#modal_role">
    Create Role
</button>
@endsection

@push('css')
<style>
    div#roleTable_filter {
        display: flex;
        justify-content: flex-end;
    }
</style>
@endpush


@push('css')
    <!-- DataTables Bootstrap 5 Theme -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css"> -->
    <style>
        /* Fix DataTable styles for Tailwind */
        table.dataTable thead th {
            background-color: #f8f9fa;
            text-transform: uppercase;
        }

        table.dataTable tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table.dataTable tbody tr:hover {
            background-color: #e2e8f0 !important;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #ccc;
            padding: 6px;
            border-radius: 4px;
        }
    </style>
@endpush

@section('content')
  <x-dynamic-table 
        :table_id="'role'" 
        :columns="['id', 'name', 'action']" 
        :route="route('roles.index')" 
    />



@endsection



<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Services\QueryFilterService;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    protected $filterService;

    public function __construct(QueryFilterService $filterService)
    {
        $this->filterService = $filterService;
    }
    public function index(Request $request)
    {
        
        $roles = Role::paginate(10); 
        $title = "Roles";
        
        if ($request->ajax()) {
            $query = Role::query();
            $filters = $request->only(['key']); 
            $filters['search'] = $request->get('search');
            $result = $this->filterService->applyFilters($query, $filters);
            // applySorting
            $shorting = ['id'=>'desc'];
            $result = $this->filterService->applySorting($result, $shorting);

            return DataTables::of($result)
                ->filter(function ($query) {
                    $query->where('name', '!=', 'client');
                })
                ->addColumn('name', function ($row) {
                    return ucwords($row->name);
                })
                ->addColumn('action', function ($row) {
                    return '<a href="javascript:void(0)" class="btn btn-warning btn-xs" data-url="'.route('roles.edit', $row->id).'" onclick="edit_swal(this)" ><i class="fas fa-edit"></i></a> ' .
                        '<form onsubmit="confirmetion_popup(this, event)" action="' . route('roles.destroy', $row->id) . '" method="POST" style="display:inline-block;">' .
                        csrf_field() .
                        method_field('DELETE') .
                        '<button type="submit" class="btn btn-danger btn-xs delete-button"><i class="fa fa-trash"></i></button>' .
                        '</form>';
                })
                ->addIndexColumn()
                ->make(true);
        }

        // return view('roles.manage_role', compact('roles'));
        return view('admin.roles.index',compact('roles','title'));
    }

    public function store(Request $request){
        Role::create(['name' => $request->name]);
        return response()->json([
            'status_code' => 1,
            'status' => 'success',
            'message' => 'Role Create Successfully',
        ]);
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return back()->with([
                'status_code' => 0,
                'status' => 'error',
                'error' => 'Role not found.'
            ]);
        }
        $role->update(['name' => $request->name]);

        return back()->with([
            'status_code' => 1,
            'status' => 'success',
            'message' => 'Role Updated successfully.',
        ]);
    }
    public function edit($id)
    {
        $role = Role::find($id);

        $columns = Schema::getColumnListing('roles');
        $excludedColumns = ['guard_name'];
       $data = create_input_fields($role, $columns,$excludedColumns);
       return response()->json([
            'edit' => true,
            'data' => $data,
            'title' => 'Edit Role',
            'route' => route('roles.update', $id)
        ]);
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return back()->with([
                'status_code' => 0,
                'status' => 'error',
                'error' => 'Role not found.'
            ]);
        }
        $role->delete();

        return back()->with([
            'status_code' => 1,
            'status' => 'success',
            'message' => 'Role deleted successfully.',
        ]);
    }

    public function show() {
        return view('roles.permission');
    }
}

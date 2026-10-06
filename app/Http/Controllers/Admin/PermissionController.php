<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\Permission;
use App\Models\Permission;
// use App\Services\QueryFilterService;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    // protected $filterService;

    // public function __construct(QueryFilterService $filterService)
    // {
    //     $this->filterService = $filterService;
    // }
    public function index(Request $request)
    {
        $data = Permission::whereNull('parent_id')->get();
        $query = Permission::query();
        $filters = $request->only(['key']);
        $filters['search'] = $request->get('search');
    
        // Get parent permissions with children
        $permissions = Permission::whereNull('parent_id')
            ->with('children')
            ->get();
    
        // Flatten permissions into a single list
        $hierarchicalPermissions = [];
        foreach ($permissions as $parent) {
            $hierarchicalPermissions[] = $parent;
            foreach ($parent->children as $child) {
                $hierarchicalPermissions[] = $child;
            }
        }
        return view('admin.roles.permission', compact('hierarchicalPermissions','data'));

        // return view('admin.roles.permission', compact('data'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name,',
            'parent_id' => 'nullable|integer|exists:permissions,id',
            'label' => 'nullable|string'
        ]);
// 'label' => Str::title(Str::replace('.', ' ', $permissionPath))
        if ($validator->fails()) {
            return show_validation_error($validator);
        }
        $data = $validator->validate();
        $createPermission = Permission::create($data);
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole && !$superAdminRole->hasPermissionTo($request->name)) {
            $superAdminRole->givePermissionTo($createPermission);
        }

        return response()->json([
            'status_code' => 1,
            'status' => 'success',
            'message' => 'Permission Create Successfully',
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'label' => 'nullable|string',
            'parent_id' => 'nullable|integer|exists:permissions,id'
        ]);

        if ($validator->fails()) {
            return show_validation_error($validator);
        }
        $data = $validator->validate();
        $permission = Permission::find($id);
        if (!$permission) {
            return back()->with([
                'status_code' => 0,
                'status' => 'error',
                'error' => 'Role not found.'
            ]);
        }
        $permission->update([
            'label' => $request->label,
            'parent_id' => $request->parent_id ?? null
        ]);

        return back()->with([
            'status_code' => 1,
            'status' => 'success',
            'message' => 'Role Updated successfully.',
        ]);
    }
    public function edit($id)
    {
        $permission = Permission::find($id);

        $columns = Schema::getColumnListing('permissions');
        $excludedColumns = ['guard_name'];
        $dropdownSources = ['parent_id' => Permission::whereNull('parent_id')->pluck('name', 'id')->toArray(),];
         $data = create_input_fields($permission, $columns, $excludedColumns, $dropdownSources);
        // dd($data);
       
        return response()->json([
            'edit' => true,
            'data' => $data,
            'title' => 'Edit Role',
            'route' => route('roles.permission.update', $id)
        ]);
    }

    public function destroy($id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return back()->with([
                'status_code' => 0,
                'status' => 'error',
                'error' => 'Permission not found.'
            ]);
        }
        $permission->delete();

        return back()->with([
            'status_code' => 1,
            'status' => 'success',
            'message' => 'Permission deleted successfully.',
        ]);
    }

    public function show()
    {
        return view('roles.permission');
    }

   public function role_permission(Request $request)
{
    // Get top-level permissions
    $permissions = Permission::whereNull('parent_id')->get();
    $roles = Role::all();

    // Manually attach children using getChildren()
    foreach ($permissions as $permission) {
        $permission->children = $permission->getChildren();
    }

    return view('admin.roles.role_permission', compact('permissions', 'roles'));
}


    public function assing_permission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|integer|exists:roles,id',
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'integer|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return show_validation_error($validator);
        }

        $data = $validator->validated();
        $role = Role::find($data['role_id']);
        
        $permissions = Permission::whereIn('id', $data['permission_ids'])->pluck('name')->toArray();
        $role->syncPermissions($permissions);

        return back()->with([
            'status_code' => 1,
            'status' => 'success',
            'message' => 'Permissions assigned successfully'
        ]);
    }

    public function permissions(Request $request)
    {
        $role = Role::find($request->role_id);
        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        $permissions = $role->permissions->pluck('id')->toArray();

        return response()->json(['permissions' => $permissions]);
    }

    
}

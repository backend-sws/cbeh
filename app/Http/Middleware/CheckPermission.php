<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission = null)
    {
        // Get the current route's name
        $routeName = $request->route()->getName();
    
        if (!$routeName) {
            abort(403, 'Route name not defined.');
        }

        if (str_starts_with($routeName, 'admin.')) {
            $routeName = substr($routeName, strlen('admin.'));
        }
    
        // Permission to check is derived from the route name
        // dd($routeName);
        $permissionToCheck = $routeName;
    
        if (!Auth::check()) {
            abort(403, 'Unauthorized action.');
        }
    
        $this->createPermissionWithParent($permissionToCheck);
    
        if (!$this->hasAccessToPermission($permissionToCheck)) {
            abort(403, 'LARAVEL_FORBIDDEN_ERROR');
        }
    
        return $next($request);
    }
    
    /**
     * Check if the user has access to a permission and its parent.
     *
     * @param string $permission
     * @return bool
     */
    protected function hasAccessToPermission($permission)
    {
        $user = Auth::user();
        
        // Bypass Spatie permission check if the user has an Admin string role
        if ($user && in_array(strtolower($user->role ?? ''), ['admin', 'super admin', 'superadmin'])) {
            return true;
        }
    
        if ($user->can($permission)) {
            $parentPermission = $this->getParentPermission($permission);
            if ($parentPermission && !$user->can($parentPermission)) {
                return false;
            }
    
            return true;
        }
    
        return false;
    }
    
    /**
     * Get the parent permission of a permission string.
     *
     * @param string $permission
     * @return string|null
     */
    protected function getParentPermission($permission)
    {
        $segments = explode('.', $permission);
    
        array_pop($segments);
        return !empty($segments) ? implode('.', $segments) : null;
    }
    
    /**
     * Create permission with parent permissions if they do not exist.
     *
     * @param string $permission
     * @return void
     */
    protected function createPermissionWithParent($permission)
    {
        $segments = explode('.', $permission);
        $permissionPath = '';
        $parentPermission = null;
    
        foreach ($segments as $segment) {
            $permissionPath = $permissionPath ? $permissionPath . '.' . $segment : $segment;
    
            $permission = Permission::firstOrCreate(
                ['name' => $permissionPath],
                ['label' => Str::title(Str::replace('.', ' ', $permissionPath))],
                ['parent_id' => $parentPermission ? $parentPermission->id : null]
            );
    
            $parentPermission = $permission;
    
            $adminRoles = Role::whereIn('name', ['Admin', 'Super Admin', 'Superadmin'])->get();
            $permissionsChanged = false;
            
            foreach ($adminRoles as $role) {
                if (!$role->hasPermissionTo($permissionPath)) {
                    try {
                        $role->givePermissionTo($permission);
                        $permissionsChanged = true;
                    } catch (\Exception $e) {
                        // Ignore duplicate entry exception
                    }
                }
            }
            
            // Also assign to the current user's role to prevent locking them out of new routes
            $user = Auth::user();
            if ($user && $user->roles) {
                foreach ($user->roles as $role) {
                    if (!$role->hasPermissionTo($permissionPath)) {
                        try {
                            $role->givePermissionTo($permission);
                            $permissionsChanged = true;
                        } catch (\Exception $e) {
                            // Ignore duplicate entry exception
                        }
                    }
                }
            }
            
            // If permissions were added, flush the Spatie cache and reload user relations
            if ($permissionsChanged) {
                app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
                if ($user) {
                    $user->unsetRelation('roles');
                    $user->unsetRelation('permissions');
                }
            }
        }
    }
    
}

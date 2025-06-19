<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleHasPermission;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RolePermisionService
{
    public function renderAdminPermissionList(): View
    {
        $permissions = Permission::paginate(10);
        return view('backend.pages.permissions', compact('permissions'));
    }
    public function renderPermissionCreateOrEdit($id = null): View
    {
        $permission = null;
        if ($id) {
            $permission = Permission::findOrFail($id);
        }
        return view('backend.pages.permission_create_or_edit', compact('permission'));
    }
    public function handlePermissionSave($request, $id = null): RedirectResponse
    {
        try {
            $request->validate([
                'name' => 'required',
                'bangla_code' => 'required',
            ]);
            $permission = $id ? Permission::findOrFail($id) : new Permission();
            $permission->name = $request->name;
            $permission->bangla_code = $request->bangla_code;
            $permission->save();
            return redirect()->route('adminPermission')->with('success', 'Permission ' . ($id ? 'updated' : 'created') . ' successfully.');
        } catch (ValidationException $th) {
            return redirect()
                ->back()
                ->withErrors($th->validator)
                ->withInput()
                ->with('error', 'Failed: ' . $th->getMessage());
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed: ' . $e->getMessage());
        }
    }
    public function handlePermissionDelete($id): RedirectResponse
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();
            return redirect()->route('adminPermission')->with('success', 'Permission deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting: ' . $e->getMessage());
        }
    }
    public function renderRoleAccess(): View
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('backend.pages.role_permissions', compact('roles', 'permissions'));
    }
    public function renderRoleAccessCreateOrEdit($id = null): View
    {
        $role = null;
        $assigned_permissions = [];

        if ($id) {
            $role = Role::with('permissions')->findOrFail($id);
            $assigned_permissions = $role->permissions->pluck('id')->toArray();
        }

        $permissions = Permission::all();

        return view('backend.pages.role_permission_create_or_edit', compact('permissions', 'role', 'assigned_permissions'));
    }
    public function handleRoleAccessSave($request, $id = null): RedirectResponse
    {
        try {
            $request->validate([
                'name' => 'required|unique:roles,name,' . ($id ?? 'NULL') . ',id',
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:permissions,id',
            ]);
            $role = $id ? Role::findOrFail($id) : new Role();
            $role->name = $request->name;
            $role->save();
            RoleHasPermission::where('role_id', $role->id)->delete();
            if ($request->has('permissions')) {
                foreach ($request->permissions as $permission_id) {
                    RoleHasPermission::create([
                        'role_id' => $role->id,
                        'permission_id' => $permission_id,
                    ]);
                }
            }
            return redirect()->route('adminRoleAccess')->with('success', 'Completed Successfully!');
        } catch (ValidationException $th) {
            return redirect()
                ->back()
                ->withErrors($th->validator)
                ->withInput()
                ->with('error', 'Failed: ' . $th->getMessage());
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed: ' . $e->getMessage());
        }
    }
    public function handleRoleAccessDelete($id): RedirectResponse
    {
        try {
            $role = Role::findOrFail($id);
            RoleHasPermission::where('role_id', $role->id)->delete();
            $role->delete();
            return redirect()->route('adminRoleAccess')->with('success', 'Role and its permissions deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RolePermisionService
{
    public function renderAdminRoleList(): View
    {
        $roles = Role::all();
        return view('backend.pages.role_list', compact('roles'));
    }
    public function renderAdminRoleCreateOrEdit($id = null): View
    {
        $role = null;
        if ($id) {
            $role = Role::findOrFail($id);
        }
        return view('backend.pages.role_create_or_edit', compact('role'));
    }
    public function handleRoleSave($request, $id = null): RedirectResponse
    {
        // dd($request->all());
        try {
            $request->validate([
                'name' => 'required|unique:roles,name,' . ($id ?? 'NULL') . ',id',
            ]);
            $role = $id ? Role::findOrFail($id) : new Role();
            $role->name = $request->name;
            $role->save();
            return redirect()->route('adminRole')->with('success', 'Role ' . ($id ? 'updated' : 'created') . ' successfully.');
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
    public function handleRoleDelete($id): RedirectResponse
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();
            return redirect()->route('adminRole')->with('success', 'Role deleted successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting: ' . $e->getMessage());
        }
    }
    public function renderAdminPermissionList(): View
    {
        $permissions = Permission::paginate(10);
        return view('backend.pages.permissions',compact('permissions'));
    }
    public function renderPermissionCreateOrEdit($id= null): View
    {
        $permission = null;
        if ($id) {
           $permission = Permission::findOrFail($id);
        }
        return view('backend.pages.permission_create_or_edit',compact('permission'));
    }
    public function handlePermissionSave($request, $id= null): RedirectResponse
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
}

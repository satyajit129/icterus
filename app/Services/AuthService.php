<?php

namespace App\Services;

use App\Enum\UserRole;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AuthService
{
    public function renderadminLogin(): View| RedirectResponse
    {
        if (Auth::check() && Auth::user()->role === UserRole::ADMIN) {
            return redirect()->intended(url()->previous());
        }
        return view('backend.pages.admin_login');
    }
    public function handleLoginRequest($request): RedirectResponse
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials)) {
                return redirect()->back()->with('error', 'Invalid credentials, please try again.');
            }
            $user = Auth::user();
            if ($user->role != UserRole::ADMIN) {
                Auth::logout();
                return redirect()->route('adminLogin')->with('error', 'You must be an admin to access this page.');
            }
            return redirect()->route('adminDashboard')->with('success', 'Login successful!');
        } catch (ValidationException $e) {
            Log::error('Validation error ! ' . $e->getMessage());
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Throwable $e) {
            Log::error('Admin login failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.');
        }
    }
    public function handleAdminLogout(): RedirectResponse
    {
        $user = Auth::user();
        if ($user) {
            Auth::logout();
        }
        return redirect()->route('adminLogin')->with('success', 'Logged out successfully.');
    }
    public function renderAdminProfile(): View
    {
        $auth_user = Auth::user()->id;
        $user = User::findOrFail($auth_user);
        return view('backend.pages.admin_profile', compact('user'));
    }
    public function handleAdminProfileUpdate($request): RedirectResponse
    {
        try {
            $user = User::findOrFail(Auth::user()->id);
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:6',
                'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            if ($request->hasFile('picture')) {
                if ($user->picture && file_exists(public_path('uploads/' . $user->picture))) {
                    unlink(public_path('uploads/' . $user->picture));
                }
                $extension = $request->picture->getClientOriginalExtension();
                $filename = 'picture_' . time() . '.' . $extension;
                $request->picture->move(public_path('uploads'), $filename);
                $user->picture = $filename;
            }
            $user->save();
            return redirect()->back()->with('success', 'Profile updated successfully.');
        } catch (ValidationException $th) {
            return redirect()
                ->back()
                ->withErrors($th->validator)
                ->withInput()
                ->with('error', 'Validation failed: ' . $th->getMessage());
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Validation failed: ' . $e->getMessage());
        }
    }
    public function renderadminUserList(): View
    {
        $admin_users = User::with('adminRole')->where('role', UserRole::ADMIN)->get();
        return view('backend.pages.admin_user_list', compact('admin_users'));
    }
    public function renderAdminUserCreateOrEdit($id = null): View
    {
        $admin_user = null;
        if ($id) {
            $admin_user = User::findOrFail($id);
        }
        $admin_roles = Role::all();
        return view('backend.pages.admin_user_create_or_edit', compact('admin_user', 'admin_roles'));
    }
    public function handleAdminUserSave($request, $id = null): RedirectResponse
    {
        // dd($request->all());
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'phone' => 'nullable|string|max:20|unique:users,phone,' . $id,
                'admin_role_id' => 'required|exists:roles,id',
            ]);
            $user = $id ? User::findOrFail($id) : new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->role = UserRole::ADMIN;
            $user->admin_role_id = $request->admin_role_id;
            if (!$id) {
                $user->password = Hash::make('123456');
            }
            $user->save();
            return redirect()->route('adminUserList')->with('success', $id ? 'Admin Updated Successfully!' : 'Admin Created Successfully!');
        } catch (ValidationException $th) {
            return redirect()
                ->back()
                ->withErrors($th->validator)
                ->withInput()
                ->with('error', 'Validation failed: ' . $th->getMessage());
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Validation failed: ' . $e->getMessage());
        }
    }
    public function handleAdminUserDelete($id): RedirectResponse
    {
        try {
            $user = User::findOrFail($id);
            if ($user->role != UserRole::ADMIN) {
                return redirect()->back()->with('error', 'You can only delete admin users.');
            }
            $user->delete();
            return redirect()->route('adminUserList')->with('success', 'Admin deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete admin: ' . $e->getMessage());
        }
    }
}

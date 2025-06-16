<?php

namespace App\Http\Controllers\Backend;

use App\Enum\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function adminLogin(): \Illuminate\View\View
    {
        return view('backend.pages.admin_login');
    }
    public function adminLoginRequest(Request $request): \Illuminate\Http\RedirectResponse
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
    public function adminLogout(): \Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();
        if ($user) {
            Auth::logout();
        }
        return redirect()->route('adminLogin')->with('success', 'Logged out successfully.');
    }
    public function adminProfile()
    {
        $auth_user = Auth::user()->id;
        $user = User::findOrFail($auth_user);
        return view('backend.pages.admin_profile', compact('user'));
    }
    public function adminProfileUpdate(Request $request): \Illuminate\Http\RedirectResponse
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

}

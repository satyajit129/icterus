<?php

namespace App\Http\Controllers\Backend;

use App\Enum\UserRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // dd($request->all());
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
        }catch (ValidationException $e) {
            Log::error('Validation error ! ' . $e->getMessage());
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Throwable $e) {
            Log::error('Admin login failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again later.');
        }
    }
}

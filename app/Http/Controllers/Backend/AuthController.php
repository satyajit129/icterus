<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\AuthService;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    protected AuthService $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function adminLogin(): View|RedirectResponse
    {
        return $this->authService->renderadminLogin();
    }
    public function adminLoginRequest(Request $request): RedirectResponse
    {
        return $this->authService->handleLoginRequest($request);
    }
    public function adminLogout(): RedirectResponse
    {
        return $this->authService->handleAdminLogout();
    }
    public function adminProfile(): View
    {
        return $this->authService->renderAdminProfile();
    }
    public function adminProfileUpdate(Request $request): RedirectResponse
    {
        return $this->authService->handleAdminProfileUpdate($request);
    }
    public function adminUserList(): View
    {
        return $this->authService->renderadminUserList();
    }
    public function adminUserCreateOrEdit($id = null): View
    {
        return $this->authService->renderAdminUserCreateOrEdit($id);
    }
    public function adminUserSave(Request $request, $id = null): RedirectResponse
    {
        return $this->authService->handleAdminUserSave($request, $id);
    }
    public function adminUserDelete($id): RedirectResponse
    {
        return $this->authService->handleAdminUserDelete($id);
    }

}

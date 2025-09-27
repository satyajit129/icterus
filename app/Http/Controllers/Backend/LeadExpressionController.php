<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\LeadExpressionService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LeadExpressionController extends Controller
{
    protected LeadExpressionService $leadExpressionService;

    public function __construct(LeadExpressionService $leadExpressionService)
    {
        $this->leadExpressionService = $leadExpressionService;
    }

    public function adminLeadExpressionList(): View
    {
        return $this->leadExpressionService->renderExpressionList();
    }

    public function adminLeadExpressionCreateOrEdit($id = null): View
    {
        return $this->leadExpressionService->renderExpressionCreateOrEditPage($id);
    }

    public function adminLeadExpressionSave(Request $request, $id = null): RedirectResponse
    {
        return $this->leadExpressionService->handleExpressionSave($request, $id);
    }

    public function adminLeadExpressionDelete($id): RedirectResponse
    {
        return $this->leadExpressionService->handleExpressionDelete($id);
    }

    public function adminLeadExpressionToggleStatus($id): RedirectResponse
    {
        return $this->leadExpressionService->handleExpressionToggleStatus($id);
    }

    public function adminLeadExpressionView($id): View
    {
        return $this->leadExpressionService->renderExpressionView($id);
    }
}

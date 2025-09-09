<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\FacebookAdAccountService;
use Illuminate\Http\Request;

class FacebookAdAccountsController extends Controller
{
    protected $facebookAdAccountService;

    public function __construct(FacebookAdAccountService $facebookAdAccountService)
    {
        $this->facebookAdAccountService = $facebookAdAccountService;
    }

    public function index(Request $request)
    {
        return $this->facebookAdAccountService->renderAdAccountsPage($request);
    }

    public function sync()
    {
        return $this->facebookAdAccountService->handleSyncAdAccountsFromApi();
    }

    public function destroy($id)
    {
        return $this->facebookAdAccountService->handleDeleteAdAccount($id);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Requests;
use App\Models\Providers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yoeunes\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public $model_view_folder;
    public function __construct()
    {
        return $this->model_view_folder = 'admin.';

    }
    public function index(){

        
    // عدد الـ providers حسب الدور
    $providersCount = Providers::select('role', DB::raw('count(*) as total'))
        ->groupBy('role')
        ->pluck('total', 'role');

    // عدد الطلبات حسب النوع
    $requestsCount = Requests::select('type', DB::raw('count(*) as total'))
        ->groupBy('type')
        ->pluck('total', 'type');

    return view($this->model_view_folder . "dashboard", [
        'providersCount' => $providersCount,
        'requestsCount' => $requestsCount
    ]);
        return view($this->model_view_folder . "dashboard");
    }
}

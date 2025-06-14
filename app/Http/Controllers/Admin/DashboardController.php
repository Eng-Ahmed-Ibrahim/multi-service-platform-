<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
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

        return view($this->model_view_folder . "dashboard");
    }
}

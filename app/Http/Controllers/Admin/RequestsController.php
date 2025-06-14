<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Requests;
use Illuminate\Http\Request;

class RequestsController extends Controller
{
    public $model_view_folder;

    public function __construct()
    {
        $this->middleware(['permission:view users'])->only('index');
        $this->middleware(['permission:update users'])->only('update');
        $this->middleware(['permission:delete users'])->only('destroy');
         $this->model_view_folder = 'admin.requests.';
    }
    public function index($type){
        $type=str_replace("-","_",$type);
        $requests=Requests::where("type",$type)->orderBy("id","DESC")->paginate(15);
        return view($this->model_view_folder ."index")
        ->with("requests",$requests)
        ->with("section",$type)
        ;
    }
    public function show($request_id){
        $request_details=Requests::where("id",$request_id)->with(["user","provider"])->first();
        if(! $request_details)
            return back();
        return view($this->model_view_folder."show")
        ->with("request_details",$request_details)
        ;
    }
}

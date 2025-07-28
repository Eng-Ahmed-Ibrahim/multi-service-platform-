<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Helpers\Helpers;
use App\Models\Services;
use App\Models\Documents;
use App\Models\Processes;
use App\Models\Providers;
use Illuminate\Http\Request;
use App\Models\ProviderService;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Yoeunes\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class HandymanController extends Controller
{
    public $model_view_folder;

    //default namespace view files

    public function __construct()
    {
        $this->middleware(['permission:view handymans'])->only('index');
        $this->middleware(['permission:update handymans'])->only('update');
        $this->middleware(['permission:delete handymans'])->only('destroy');
        $this->model_view_folder = 'admin.handymans.';
    }
    public function index()
    {
        $handymans = Providers::where("role", "handyman")->orderBy("id", "DESC")->paginate(15);
        return view($this->model_view_folder . "index")
            ->with("handymans", $handymans);
    }
    public function create()
    {
        $services = Services::where("section", "!=", 'car_transportations')->orderBy("id", "DESC")->get();
        return view($this->model_view_folder . "create")->with("services", $services);
    }
    public function show($handyman_id)
    {

        $driver = Providers::with(['documents', 'carType' => function ($query) {
            $query->select('id', 'name', 'name_ar');
        }, 'brand', 'model', 'processes', 'services.service', 'requests', 'walletTransactions' => function ($query) {
            $query->latest(); // or ->orderBy('created_at', 'desc')
        }])->find($handyman_id);
        // return $driver;
        $groupedDocuments = $driver->documents->groupBy('process_number');
        return view($this->model_view_folder . "show")
            ->with("driver", $driver)
            ->with("groupedDocuments", $groupedDocuments)
        ;
    }
    public function store(Request $request)
    {
        $request->validate([
            "f_name" => "required|string|max:255",
            "l_name" => "required|string|max:255",
            "phone" => "required|unique:providers|regex:/^([0-9\s\-\+\(\)]*)$/",
            'password' => 'required|min:8',
            "address" => "required",
            'date_of_birth' => 'required|date|before:today',
            "country_code"=>"required",

            "id_number" => "required",
            "gender" => "required",
            "email" => "required|email|unique:providers",
            'photo' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'criminal_record' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'id_front' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'id_back' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'services' => 'required|array',
            'services.*' => 'exists:services,id',
        ]);

        DB::beginTransaction();

        try {
            $dateOfBirth = Carbon::createFromFormat('Y-m-d', $request->date_of_birth)->format('Y-m-d');
            $user = Providers::create([
                "name" => $request->f_name . ' ' . $request->l_name,
                "password" => Hash::make($request->password),
                "image" => Helpers::upload_files($request->photo, "/uploads/handymans/account_photos/"),
                "role" => "handyman",
                "email" => $request->email,
                "phone" =>"+". $request->country_code . $request->phone,
                "address" => $request->address,
                "gender" => $request->gender,
                "id_number" => $request->id_number,
                "date_of_birth" => $dateOfBirth,
            ]);
            $processes = [
                [
                    "process_number" => 1,
                    "name" => "personal_information",
                ],
                [
                    "process_number" => 2,
                    "name" => "official_documents",
                ]
            ];
            $storedProcesses = [];
            foreach ($processes as $process) {
                $storedProcesses[] = [
                    "provider_id" => $user->id,
                    "process_number" => $process['process_number'],
                    "name" => $process['name'],
                ];
            }
            Processes::insert($storedProcesses);
            $providerServices = [];
            foreach ($request->services as $service_id) {
                $providerServices[] = [
                    "service_id" => $service_id,
                    "provider_id" => $user->id,
                ];
            }
            ProviderService::insert($providerServices);


            $documents = [
                'id_front' => [$request->id_front, 2],
                'id_back' => [$request->id_back, 2],
                'criminal_record' => [$request->criminal_record, 2],
            ];

            $documentsToStore = [];
            foreach ($documents as $key => $file) {
                if (!empty($file[0])) {
                    $filePath = Helpers::upload_files($file[0], '/uploads/drivers/documents/');
                    $documentsToStore[] = [
                        'provider_id' => $user->id,
                        'name' => $key,
                        "process_number" => $file[1],
                        'attachment' => $filePath,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            Documents::insert($documentsToStore);


            DB::commit();


            Toastr::success(trans('messages.Added_successfully'));
            return redirect()->route('admin.handymans.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error($e->getMessage());
            return back();
        }
    }
    public function delete($handyman_id)
    {
        $handyman = Providers::where("role", "handyman")->find($handyman_id);
        Helpers::delete_file($handyman->image);
        $handyman->delete();
        Toastr::success(__("messages.Deleted_successfully"));
        return back();
    }
    public function update(Request $request)
    {
        $request->validate([
            "handyman_id" => "required",
            "name" => "required",
            "phone" => "required",
            "email" => "required",
            
        ]);
        $handyman = Providers::where("role", "handyman")->find($request->handyman_id);
        if (! $handyman)
            return back();

        if ($request->hasFile("avatar")) {
            Helpers::delete_file($handyman->image);
            $handyman->update([
                "image" => Helpers::upload_files($request->avatar, '/uploads/handymans/'),
            ]);
        }

        $handyman->update([
            "name" => $request->name,
            "phone" => $request->phone,
            "email" => $request->email,
        ]);
        Toastr::success(__("messages.Updated_successfully"));
        return back();
    }
}

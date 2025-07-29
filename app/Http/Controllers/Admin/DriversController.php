<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Brands;
use App\Models\Models;
use App\Helpers\Helpers;
use App\Models\Services;
use App\Models\Documents;
use App\Models\Processes;
use App\Models\Providers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use NunoMaduro\Collision\Provider;
use Spatie\Permission\Models\Role;
use Yoeunes\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use League\CommonMark\Node\Block\Document;
use SebastianBergmann\CodeCoverage\Driver\Driver;

class DriversController extends Controller
{
    public $model_view_folder;

    //default namespace view files

    public function __construct()
    {
        $this->middleware(['permission:view drivers'])->only('index');
        $this->middleware(['permission:update drivers'])->only('update');
        $this->middleware(['permission:delete drivers'])->only('destroy');
        $this->model_view_folder = 'admin.drivers.';
    }
    public function index()
    {
        $drivers = Providers::where("role", "driver")->orderBy("id", "DESC")->paginate(15);
        return view($this->model_view_folder . "index")
            ->with("drivers", $drivers);
    }
    public function create()
    {
        return view($this->model_view_folder . "create");
    }
    public function edit($id)
    {
        $driver = Providers::with(['documents', 'carType' => function ($query) {
            $query->select('id', 'name', 'name_ar');
        }, 'brand', 'model', 'processes', 'requests', 'walletTransactions' => function ($query) {
            $query->latest();
        }])->findOrFail($id);
        $car_types=Services::where("status",true)->where("section",'car_transportations')->orderBy("id","DESC")->get();
        $brands=Brands::where("status",true)->where("service_id",$driver->car_type)->orderBy("id","DESC")->get();    
        $models=Models::where("status",true)->where("brand_id",$driver->brand_id)->orderBy("id","DESC")->get();

        return view($this->model_view_folder . "edit", compact('driver','car_types', 'brands', 'models'))
            ->with("driver", $driver);
    }
    public function show($driver_id)
    {
        $driver = Providers::with(['documents', 'carType' => function ($query) {
            $query->select('id', 'name', 'name_ar');
        }, 'brand', 'model', 'processes', 'requests', 'walletTransactions' => function ($query) {
            $query->latest();
        }])->find($driver_id);
        $groupedDocuments = $driver->documents->groupBy('process_number');
        return view($this->model_view_folder . "show")
            ->with("driver", $driver)
            ->with("groupedDocuments", $groupedDocuments)
        ;
    }
    public function store(Request $request)
    {
        $request->validate([
            "country_code"=>"required",

            "f_name" => "required|string|max:255",
            "l_name" => "required|string|max:255",
            "phone" => "required|unique:providers|regex:/^([0-9\s\-\+\(\)]*)$/",
            'password' => 'required|min:8',
            "address" => "required",
            'gender' => ['required', 'in:male,female'],
            'account_photo' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'date_of_birth' => 'required|date|before:today',

            'id_front' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'id_back' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'criminal_record' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            "id_number" => "required",

            'driving_license_front' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'driving_license_back' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'photo_with_driving_license' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'driving_license_issue_date' => 'required',
            'driving_license_expiry_date' => ['required', 'date', 'after:today'],

            // 'car_license_expiration' => 'required|date|after:today',
            // 'driving_license_expiration' => 'required|date|after:today',
            "production_year" => "required",
            "car_color" => "required",
            "car_plate_number" => "required",
            'car_type' => 'required',
            'car_type.*' => 'exists:services,id',
            'brand_id' => 'required',
            'brand_id.*' => 'exists:brands,id',
            'model_id' => 'required',
            'model_id.*' => 'exists:models,id',
            'license_front' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'license_back' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'car_photo' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',

        ]);

        DB::beginTransaction();

        try {
            // $carLicenseExpiration = Carbon::createFromFormat('Y-m-d', $request->car_license_expiration)->format('Y-m-d');
            // $drivingLicenseExpiration = Carbon::createFromFormat('Y-m-d', $request->driving_license_expiration)->format('Y-m-d');
            $dateOfBirth = Carbon::createFromFormat('Y-m-d', $request->date_of_birth)->format('Y-m-d');

            $user = Providers::create([

                "name" => $request->f_name . ' ' . $request->l_name,
                "email" => $request->email,
                "phone" =>"+". $request->country_code . $request->phone,
                "address" => $request->address,
                "password" => Hash::make($request->password),
                "image" => Helpers::upload_files($request->account_photo, "/uploads/drivers/account_photos/"),
                "date_of_birth" => $dateOfBirth,
                "gender" => $request->gender,
                "id_number" => $request->id_number,
                "role" => "driver",
                "production_year" => $request->production_year,
                "car_color" => $request->car_color,
                "car_type" => $request->car_type,
                "car_plate_number" => $request->car_plate_number,
                "brand_id" => $request->brand_id,
                "model_id" => $request->model_id,
                "driving_license_expiry_date" => $request->driving_license_expiry_date,
                "driving_license_issue_date" => $request->driving_license_issue_date,
                // "car_license_expiration" => $carLicenseExpiration,
                // "driving_license_expiration" => $drivingLicenseExpiration,
            ]);
            $processes = [
                [
                    "process_number" => 1,
                    "name" => "personal_information",
                ],
                [
                    "process_number" => 2,
                    "name" => "official_documents",
                ],
                [
                    "process_number" => 3,
                    "name" => "licenses",
                ],
                [
                    "process_number" => 4,
                    "name" => "vehicle_information",
                ]
            ];
            foreach ($processes as $process) {
                $process['provider_id'] = $user->id;
                Processes::create($process);
            }
            $documents = [
                'criminal_record' => [$request->criminal_record, 2],
                'id_front' => [$request->id_front, 2],
                'id_back' => [$request->id_back, 2],

                'license_front' => [$request->license_front, 4],
                'license_back' => [$request->license_back, 4],
                'car_photo' => [$request->car_photo, 4],

                'driving_license_front' => [$request->driving_license_front, 3],
                'driving_license_back' => [$request->driving_license_back, 3],
                'photo_with_driving_license' => [$request->photo_with_driving_license, 3],
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


            Toastr::success(__('messages.Registered_successfully'));
            return redirect()->route('admin.drivers.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error($e->getMessage());
            return back();
        }
    }
    public function delete($driver_id)
    {
        $driver = Providers::where("role", "driver")->find($driver_id);
        Helpers::delete_file($driver->image);
        $driver->delete();
        Toastr::success(__("messages.Deleted_successfully"));
        return back();
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "phone" => [
                'required',
                'regex:/^([0-9\s\-\+\(\)]*)$/',
                Rule::unique('providers', 'phone')->ignore($id)
            ],
            'password' => 'nullable|min:8',
            "address" => "required",
            'gender' => ['required', 'in:male,female'],
            'account_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'date_of_birth' => 'required|date|before:today',

            'id_front' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'id_back' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'criminal_record' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            "id_number" => "required",

            'driving_license_front' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'driving_license_back' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'photo_with_driving_license' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'driving_license_issue_date' => 'required',
            'driving_license_expiry_date' => ['required', 'date', 'after:today'],

            // 'car_license_expiration' => 'required|date|after:today',
            // 'driving_license_expiration' => 'required|date|after:today',
            "production_year" => "required",
            "car_color" => "required",
            "car_plate_number" => "required",
            'car_type' => 'required',
            'car_type.*' => 'exists:services,id',
            'brand_id' => 'required',
            'brand_id.*' => 'exists:brands,id',
            'model_id' => 'required',
            'model_id.*' => 'exists:models,id',
            'license_front' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'license_back' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'car_photo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            "country_code"=>"required",

        ]);

        DB::beginTransaction();

        try {
            // $carLicenseExpiration = Carbon::createFromFormat('Y-m-d', $request->car_license_expiration)->format('Y-m-d');
            // $drivingLicenseExpiration = Carbon::createFromFormat('Y-m-d', $request->driving_license_expiration)->format('Y-m-d');
            $dateOfBirth = Carbon::createFromFormat('Y-m-d', $request->date_of_birth)->format('Y-m-d');

            $user = Providers::find($id);
            $user->update([

                "name" => $request->name,
                "email" => $request->email,
                "phone" =>"+". $request->country_code . $request->phone,
                "address" => $request->address,
                "password" => Hash::make($request->password),
                "date_of_birth" => $dateOfBirth,
                "gender" => $request->gender,
                "id_number" => $request->id_number,
                "role" => "driver",
                "production_year" => $request->production_year,
                "car_color" => $request->car_color,
                "car_type" => $request->car_type,
                "car_plate_number" => $request->car_plate_number,
                "brand_id" => $request->brand_id,
                "model_id" => $request->model_id,
                "driving_license_expiry_date" => $request->driving_license_expiry_date,
                "driving_license_issue_date" => $request->driving_license_issue_date,
                // "car_license_expiration" => $carLicenseExpiration,
                // "driving_license_expiration" => $drivingLicenseExpiration,
            ]);
            if($request->hasFile('account_photo')) {
                Helpers::delete_file($user->image);
                $user->image = Helpers::upload_files($request->account_photo, "/uploads/drivers/account_photos/");
                $user->save();
            }
            $documents = [
                'criminal_record' => [$request->criminal_record, 2],
                'id_front' => [$request->id_front, 2],
                'id_back' => [$request->id_back, 2],

                'license_front' => [$request->license_front, 4],
                'license_back' => [$request->license_back, 4],
                'car_photo' => [$request->car_photo, 4],

                'driving_license_front' => [$request->driving_license_front, 3],
                'driving_license_back' => [$request->driving_license_back, 3],
                'photo_with_driving_license' => [$request->photo_with_driving_license, 3],
            ];

            foreach ($documents as $key => $file) {
                if (!empty($file[0])) {
                    $doc = Documents::where("name", $key)->where("provider_id", $user->id)->first();
                    if (!$doc)
                        abort(404);
                    Helpers::delete_file($doc->attachment);
                    $filePath = Helpers::upload_files($file[0], '/uploads/drivers/documents/');
                    $doc->update([
                        "attachment" => $filePath,
                    ]);
                }
            }


            DB::commit();


            Toastr::success(__('messages.Updated_successfully'));
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error($e->getMessage());
            return back();
        }
    }

    public function change_process_status(Request $request)
    {
        $request->validate([
            "driver_id" => "required",
            "process_number" => "required",
            "status" => "required",
        ]);
        $process = Processes::where("provider_id", $request->driver_id)
            ->where("process_number", $request->process_number)->first();
        if (! $process) {
            Toastr::error(__("messages.Not_found"));
            return back();
        }

        $process->status = $request->status;
        if ($request->status == "rejected") {
            $process->rejection_reason = $request->rejection_reason;
        }
        $process->save();
        Toastr::success(__("messages.Updated_successfully"));
        session()->put("current_process_number", $request->process_number);
        $previousUrl = url()->previous();

        $parsedUrl = parse_url($previousUrl);
        $queryParams = [];
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
        }
        $queryParams['current_process_number'] = $request->process_number;
        $newUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'] . '?' . http_build_query($queryParams);

        return redirect()->to($newUrl);
    }
}

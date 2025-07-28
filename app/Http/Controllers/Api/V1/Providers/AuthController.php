<?php

namespace App\Http\Controllers\Api\V1\Providers;

use Carbon\Carbon;
use App\Models\User;
use App\Helpers\Helpers;
use App\Models\Documents;
use App\Models\Processes;
use App\Models\Providers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProviderService;
use App\Services\AddNewFcmService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseTrait;
use App\Services\CheckAccountVerifiedService;

class AuthController extends Controller
{
    use ResponseTrait;

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "phone" => "required",
            "password" => "required",
            "fcm_token" => "required",
            "device_id" => "required",
        ]);
        
        if ($validator->fails()) {
            return $this->Response($validator->errors()->keys(), "Validation Error", 422);
        }
        
        $phone = $request->phone;

        if (!Str::startsWith($phone, '+')) {
            $phone = '+' . $phone;
        }
        $user = Providers::where("phone", $phone)->first();
        
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->Response("Incorrect phone or password", "Incorrect phone or password", 401);
        }
        AddNewFcmService::addFcm($user,$request->fcm_token,$request->device_id);
        $token = $user->createToken('Driver Token')->plainTextToken;
        $data = [
            "user" => $user,
            "token" => $token,
        ];
        $verificationResponse = CheckAccountVerifiedService::checkVerificationStatus($user,$token,403);
        if ($verificationResponse) {
            return $verificationResponse;
        }
        return $this->Response($data, __("messages.Login Successfully"), 200);
    }


    public function register_driver(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "f_name" => "required|string|max:255",
            "l_name" => "required|string|max:255",
            "phone" => "required|unique:providers|regex:/^([0-9\s\-\+\(\)]*)$/",
            'password' => 'required|min:8',
            "address" => "required",
            "id_number" => "required",
            'gender' => ['required', 'in:male,female'],
            'account_photo' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'date_of_birth' => 'required|date|before:today',

            'id_front' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'id_back' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'criminal_record' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'license_front' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'license_back' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'car_photo' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'driving_license_front' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'driving_license_back' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'driving_license_issue_date' => 'required',
            'driving_license_expiry_date' => 'required',
            'car_license_expiration' => 'required|date|after:today',
            'driving_license_expiration' => 'required|date|after:today',
            'photo_with_driving_license' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            "production_year"=>"required",
            "car_color"=>"required",
            "car_plate_number"=>"required",
            'car_type' => 'required',
            'car_type.*' => 'exists:services,id',
            'brand_id' => 'required',
            'brand_id.*' => 'exists:brands,id',

            'model_id' => 'required',
            'model_id.*' => 'exists:models,id',
            "fcm_token" => "required",
            "device_id" => "required",
        ]);
    
        if ($validator->fails()) {
            return $this->Response($validator->errors()->keys(), "Validation Error", 422);
        }
    
        DB::beginTransaction();
    
        try {
            $carLicenseExpiration = Carbon::createFromFormat('m/d/Y', $request->car_license_expiration)->format('Y-m-d');
            $drivingLicenseExpiration = Carbon::createFromFormat('m/d/Y', $request->driving_license_expiration)->format('Y-m-d');
            $dateOfBirth = Carbon::createFromFormat('m/d/Y', $request->date_of_birth)->format('Y-m-d');
            
            $user = Providers::create([

                "name" => $request->f_name . ' ' . $request->l_name,
                "email" => $request->email,
                "phone" => $request->phone,
                "address" => $request->address,
                "password" => Hash::make($request->password),
                "image" => Helpers::upload_files($request->account_photo, "/uploads/drivers/account_photos/"),
                "date_of_birth" => $dateOfBirth,
                "gender"=>$request->gender,
                "id_number"=>$request->id_number,
                "role" => "driver",
                "production_year"=>$request->production_year,
                "car_color"=>$request->car_color,
                "car_type"=>$request->car_type,
                "car_plate_number"=>$request->car_plate_number,
                "brand_id"=>$request->brand_id,
                "model_id"=>$request->model_id,
                "car_license_expiration" => $carLicenseExpiration,
                "driving_license_expiration" => $drivingLicenseExpiration,
                "driving_license_expiry_date"=>$request->driving_license_expiry_date,
                "driving_license_issue_date"=>$request->driving_license_issue_date,
            ]);
            AddNewFcmService::addFcm($user,$request->fcm_token,$request->device_id);

            $processes=[
                [
                    "process_number"=>1,
                    "name"=>"personal_information",
                ],
                [
                    "process_number"=>2,
                    "name"=>"official_documents",
                ],
                [
                    "process_number"=>3,
                    "name"=>"licenses",
                ],
                [
                    "process_number"=>4,
                    "name"=>"vehicle_information",
                ]
            ];
            foreach ($processes as $process) {
                $process['provider_id']=$user->id;
                Processes::create($process);
            }
            $documents = [
                'criminal_record' => [$request->criminal_record,2],
                'id_front' => [$request->id_front,2],
                'id_back' => [$request->id_back,2  ],

                'license_front' => [$request->license_front,4],
                'license_back' => [$request->license_back,4],
                'car_photo' => [$request->car_photo,4],

                'driving_license_front' => [$request->driving_license_front,3],
                'driving_license_back' => [$request->driving_license_back,3],
                'photo_with_driving_license' => [$request->photo_with_driving_license,3],
            ];
    
            $documentsToStore = [];
            foreach ($documents as $key => $file) {
                if (!empty($file[0])) {
                    $filePath = Helpers::upload_files($file[0], '/uploads/drivers/documents/');
                    $documentsToStore[] = [
                        'provider_id' => $user->id,
                        'name' => $key,
                        "process_number"=>$file[1],
                        'attachment' => $filePath,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
    
            Documents::insert($documentsToStore);
    
            $token = $user->createToken('Driver Token', ['*'])->plainTextToken;
            DB::commit();
            
            $data = [
                'user' => $user,
                "token" => $token,
            ];
            
            $verificationResponse = CheckAccountVerifiedService::checkVerificationStatus($user,$token,200);
            if ($verificationResponse) {
                return $verificationResponse; // لو الحساب غير مفعل، يرجع رسالة الخطأ
            }
            return $this->Response($data, __("messages.Registered Successfully"), 201);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->Response($e->getMessage(), __("messages.Registration Failed"), 500);
        }
    }

    public function register_handyman(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "f_name" => "required|string|max:255",
            "l_name" => "required|string|max:255",
            "phone" => "required|unique:providers|regex:/^([0-9\s\-\+\(\)]*)$/",
            'password' => 'required|min:8',
            "address" => "required",
            'date_of_birth' => 'required|date|before:today',

            "id_number" => "required",
            "gender" => "required",
            "email" => "required|email|unique:providers",
            'photo' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'criminal_record' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'id_front' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'id_back' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'services' => 'required|array',
            'services.*' => 'exists:services,id',
            "fcm_token" => "required",
            "device_id" => "required",
        ]);
    
        if ($validator->fails()) {
            return $this->Response($validator->errors()->keys(), __("messages.Validation Error"), 422);
        }
    
        DB::beginTransaction();
    
        try {
            $dateOfBirth = Carbon::createFromFormat('m/d/Y', $request->date_of_birth)->format('Y-m-d');
            $user = Providers::create([
                "name" => $request->f_name . ' ' . $request->l_name,
                "password" => Hash::make($request->password),
                "image" => Helpers::upload_files($request->photo, "/uploads/handymans/account_photos/"),
                "role" => "handyman",
                "email" => $request->email,
                "phone" => $request->phone,
                "address" => $request->address,
                "gender" => $request->gender,
                "id_number" => $request->id_number,
                "date_of_birth" => $dateOfBirth,
            ]);
            AddNewFcmService::addFcm($user,$request->fcm_token,$request->device_id);

            $processes=[
                [
                    "process_number"=>1,
                    "name"=>"personal_information",
                ],
                [
                    "process_number"=>2,
                    "name"=>"official_documents",
                ]
            ];
            $storedProcesses=[];
            foreach ($processes as $process) {
                $storedProcesses[]=[
                    "provider_id"=>$user->id,
                    "process_number"=>$process['process_number'],   
                    "name"=>$process['name'],   
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
                'id_front' => [$request->id_front,2],
                'id_back' => [$request->id_back,2],
                'criminal_record' =>[ $request->criminal_record,2],
            ];
    
            $documentsToStore = [];
            foreach ($documents as $key => $file) {
                if (!empty($file[0])) {
                    $filePath = Helpers::upload_files($file[0], '/uploads/drivers/documents/');
                    $documentsToStore[] = [
                        'provider_id' => $user->id,
                        'name' => $key,
                        "process_number"=>$file[1],
                        'attachment' => $filePath,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
    
            Documents::insert($documentsToStore);

            $token = $user->createToken('Handyman Token', ['*'])->plainTextToken;
    
            DB::commit();
    
            $data = [
                'user' => $user,
                "token" => $token,
            ];
            $verificationResponse = CheckAccountVerifiedService::checkVerificationStatus($user,$token,200);
            if ($verificationResponse) {
                return $verificationResponse; // لو الحساب غير مفعل، يرجع رسالة الخطأ
            }
            return $this->Response($data, __("messages.Registered Successfully"), 201);

    
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->Response($e->getMessage(), __("messages.Registration Failed"), 500);
        }
    }
    

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->Response(null, __("messages.Logged Out Successfully"), 200);
    }
 
    
}

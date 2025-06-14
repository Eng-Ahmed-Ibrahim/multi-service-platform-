<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Helpers;
use App\Models\Documents;
use App\Models\Processes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseTrait;

class VerifyAccountController extends Controller
{
    use ResponseTrait;

    public function verify_phone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "otp" => "required",
        ]);
        if ($validator->fails())
            return $this->Response($validator->errors()->first(), "Validation Error", 422);

        $user = $request->user();
        if ($request->otp == 00000) {
            $user->phone_verified = 1;

            $user->save();
            if ($user->role != "user") {
                Processes::where("process_number", 1)->where("provider_id", $user->id)->update(["status" => "accepted"]);
            }
            return $this->Response(null, "Phone number verified successfully", 200);
        }

        return $this->Response(null, "Phone number is available", 200);
    }

    public function verify_account(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "step" => "required|array", // 🔹 تأكد أن step مصفوفة
        ]);

        if ($validator->fails()) {
            return $this->Response($validator->errors()->first(), "Validation Error", 422);
        }

        $files = $request->allFiles();
        $user = $request->user();
        $tableColumns = array_diff(
            Schema::getColumnListing($user->getTable()),
            ['password', 'remember_token', 'id', 'created_at', 'updated_at', 'deleted_at', 'role', 'phone_verified', 'is_verified', 'blocked', 'status', 'phone']
        );

        $texts = collect($request->all())
            ->except(array_merge(array_keys($files), ['step']))
            ->filter(fn($value, $key) => in_array($key, $tableColumns)) // ✅ السماح فقط بالحقول المصرح بها 
            ->toArray();

        if (!empty($texts)) {
            $user->update($texts);
        }

        // 🔹 معالجة كل خطوة في المصفوفة
        foreach ($request->step as $step) {
            // 🔹 تحديث الملفات لكل خطوة
            foreach ($files as $index => $file) {
                $document = Documents::where("name", $index)
                    ->where("process_number", $step)
                    ->where("provider_id", $user->id)
                    ->first();

                if ($document) {
                    Helpers::delete_file($document->attachment);
                    $path = $user->role == "driver" ? "/uploads/drivers/documents/" : "/uploads/handymans/documents/";
                    $document->update([
                        "attachment" => Helpers::upload_files($file, $path)
                    ]);
                    unset($files[$index]);
                }
                
            }

            // 🔹 تحديث حالة العملية لكل خطوة
            Processes::where("process_number", $step)
                ->where("provider_id", $user->id)
                ->update(["status" => "pending"]);
        }

        return $this->Response(
            null,
            "Your information has been updated, and your account is now under review. You will be notified once the verification process is complete.",
            200
        );
    }
}

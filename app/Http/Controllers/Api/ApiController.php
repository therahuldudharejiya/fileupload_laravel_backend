<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\z;
use Illuminate\Support\Facades\Validator;
use App\Models\Fileupload;

class ApiController extends Controller {

    public function upload_file(Request $request){
        $validator = Validator::make($request->all(), [
            'device_model'=>'required|string',
            'firmware_version'=>'required',
            'firmware_file'=>'required'
        ]);
        $errors = [];
        if ($validator->fails()) {
            $messages = $validator->messages()->toArray();
            foreach ($messages as $message) {
                foreach ($message as $msg);
                return response()->json([
                    'success' => 0,
                    'message' => $msg
                ], 200);
            }
        }
        if($request->has('firmware_file')){
            $file = $request->file('firmware_file');
            $fileName = $file->getClientOriginalName();
            $finalName = date('His').$fileName;
            $file->move('firmwareFiles/',$finalName);
            $data = new Fileupload();
            $data->device_model = $request->device_model;
            $data->firmware_version = $request->firmware_version;
            $data->firmware_file = 'firmwareFiles/'.$finalName;
            $data->save();
            return response()->json([
                "status"=>1,
                "message"=>"Device Firmware Updated Successfully"
            ]);
        } else {
            return response()->json([
                "status"=>0,
                "message"=>"Error plz Try Again"
            ]);
        }
    }

    public function list_files(){
        return Fileupload::all();
    }
}

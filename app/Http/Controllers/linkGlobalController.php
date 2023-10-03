<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LinkGlobal;
use Illuminate\Support\Facades\Validator;

class linkGlobalController extends Controller
{

    function all_link(){
        return LinkGlobal::where("is_status", 1)->get();
    }

    function find_one($id){
        $data = LinkGlobal::where([
                'id'=> $id,
                'is_status' => 1
            ])->get();
        return response([
            'data' => $data
        ]);
    }

    function add_link(Request $request){
       
        $validator = Validator::make($request->input(), [
            'name' => 'required|max:255',
            'url'  => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                "message" => "failed"
            ], 500);
        }

        LinkGlobal::create($request->all());

        return response([
            "message" => "success"
        ]);

    }

    public function update(Request $request, $id){
        try {
            
            $validator = Validator::make($request->input(), [
                'name' => 'required|max:255',
                'url'  => 'required',
            ]);
    
            if ($validator->fails()) {
                return response([
                    "message" => "failed"
                ], 500);
            }
            
            LinkGlobal::where('id', $id)->update($request->all());
            return response()->json([
                'message' => 'update success'
            ]);

        } catch (Exception $error) {
            // if error
            return response()->json([
                'message' => 'Something went Wrong!',
                'error' => $error
            ], 500);
        }
    }

    public function delete($id){

        LinkGlobal::where('id', $id)->update([
            'is_status' => 0
        ]);
        return response([
            'message' => 'delete success'
        ]);

    }

}

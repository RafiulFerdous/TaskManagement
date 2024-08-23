<?php
namespace App\Traits;
trait HttpResponses
{


    protected function success(string $message='', $data, $code = 200){
        return response()->json([
            'status'=>true,
            'message'=>$message,
            'data'=> $data

        ], $code);

    }



    protected function error($message, $code=400){
        return response()->json([
            'status'=>false,
            'message'=>$message,

        ], $code);

    }

   protected function respondError($message = '', $code = 500, $data = [], $key = 'data', $status = false)
    {
        return response()->json([
            'success' => $status,
            'message' => $message,
            "{$key}" => $data
        ], $code);
    }



}

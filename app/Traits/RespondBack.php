<?php

namespace App\Traits;

Trait RespondBack
{

    public function ResponseFail($msg)
    {
        return response()->json(['status'=> false, 'msg' => $msg]);
    }

    public function ResponseSuccessMessage($msg){
        return response()->json(['status' => true, 'msg' => $msg]);
    }

    public function ResponseSuccessData($data){
        return response()->json(['status' => true, 'data' => $data]);
    }

}

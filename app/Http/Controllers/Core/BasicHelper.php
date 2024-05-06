<?php
namespace App\Http\Controllers\Core;

class BasicHelper
{
    public function phoneFilter($phone)
    {
        $phone = str_replace(array('\\', '/', ':', '*', '"', '<', '>'), '', preg_replace('/\s+/', '', trim($phone)));
        if (substr($phone, 0, 3) == "+84") {
            $phone = "0" . substr($phone, 3);
        } elseif (substr($phone, 0, 2) == "84") {
            $phone = "0" . substr($phone, 2);
        } elseif (substr($phone, 0, 1) != "0") {
            return ["code" => 12];
        } else return ["code" => 99];

        $phoneLength = strlen($phone);
        if ($phoneLength != 10) {
            return ["code" => 11];
        }

        return ["code" => 1, "phone" => $phone];
    }

    public function response_form($statusCode, $code, $message="", $data=[]){
        return response()->json([
            'status' => $code,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
}

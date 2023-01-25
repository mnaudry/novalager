<?php
namespace App\Helpers;

class ProductHelper {

    public static function createError($error, $code) {
        return response()->json(["errors" => $error ],$code);
    }
}
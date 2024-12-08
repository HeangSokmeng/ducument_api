<?php

namespace App\Services;

use App\Models\User;
use DataResponse;

class UserService{
    public static function getAuthUser(){
        $user = auth()->user();
        if($user){
            $existUser = User::find($user->id);
            return DataResponse::JsonRaw([
                'error' => false,
                'company_id' => $existUser->company_id,
                'user_id' => $existUser->id,
                'branch_id' => $existUser->branch_id,
                'id' => $existUser->id,
                'info' => $existUser
            ]);
        }
        return DataResponse::Unauthorized();
    }
}

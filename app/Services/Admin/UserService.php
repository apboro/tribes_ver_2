<?php

namespace App\Services\Admin;

use App\Models\Administrator;
use App\Models\User;

class UserService
{

    public function toggleAdminPermissions(User $user): bool
    {
        if($record = Administrator::where('user_id',$user->id)->first()) {
            $record->delete();
            return false;
        } else {
            $admin = new Administrator();
            $admin->user_id = $user->id;
            $admin->save();
            return true;
        }

    }
}
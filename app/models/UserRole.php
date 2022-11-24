<?php

namespace App\Models;

use App\Helpers\Helpers;
use App\Lib\Model;

class UserRole extends Model {
    public string $table = "user_roles";

    public function role() {
        return $this->belongsTo(Role::class, "role_id", "id");
    }
}
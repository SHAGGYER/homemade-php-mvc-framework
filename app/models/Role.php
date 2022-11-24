<?php

namespace App\Models;

use App\Helpers\Helpers;
use App\Lib\Model;

class Role extends Model {
    public string $table = "roles";

    public function user() {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
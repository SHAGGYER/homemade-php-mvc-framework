<?php

namespace App\Models;

use App\Helpers\Helpers;
use App\Lib\Model;

class Token extends Model {
    public string $table = "tokens";

    public function user() {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
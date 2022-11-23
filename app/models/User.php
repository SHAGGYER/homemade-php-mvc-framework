<?php

namespace App\Models;

use App\Lib\Model;
use App\Traits\HasApiTokens;
use stdClass;

class User extends Model {
    use HasApiTokens;

    protected string $table = "users";
    
    
}
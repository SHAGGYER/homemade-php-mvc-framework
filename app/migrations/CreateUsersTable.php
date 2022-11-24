<?php

namespace App\Migrations;

use App\Lib\Migration;

class CreateUsersTable extends Migration {
    public function up() {
        return <<<EOF
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
);
EOF;
    }

    public function down() {
        return <<<EOF
DROP TABLE IF EXISTS users;
EOF;
    }
}
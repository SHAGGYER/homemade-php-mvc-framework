<?php
namespace App\Migrations;

use App\Lib\Migration;

class CreateTodosTable extends Migration
{
    public function up() {
        return <<<EOT
CREATE TABLE IF NOT EXISTS todos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    completed TINYINT DEFAULT 0,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
);
EOT;
    }

    public function down() {
        return <<<EOT
DROP TABLE IF EXISTS todos;
EOT;
    }
}
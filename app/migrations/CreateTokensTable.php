<?php
namespace App\Migrations;

use App\Lib\Migration;

class CreateTokensTable extends Migration
{
    public function up() {
        return <<<EOT
CREATE TABLE IF NOT EXISTS tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
);
EOT;
    }

    public function down() {
        return <<<EOT
DROP TABLE IF EXISTS tokens;
EOT;
    }
}
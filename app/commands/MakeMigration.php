<?php

namespace App\Commands;

class MakeMigration {
    public $controllerName;

    public function handle($controllerName) {
        $this->controllerName = $controllerName;
        $controllerFile = fopen(APP_PATH . "/migrations/" . $controllerName . ".php", "w") or die("Unable to open file!");

        $controllerContent = $this->getFileContents();
        fwrite($controllerFile, $controllerContent);
        fclose($controllerFile);
    }

    private function getFileContents(): string {
        return <<<EOF
<?php
namespace App\Migrations;

use App\Lib\Migration;

class $this->controllerName extends Migration
{
    public function up() {
        return <<<EOT

EOT;
    }

    public function down() {
        return <<<EOT

EOT;
    }
}
EOF;
    }
}
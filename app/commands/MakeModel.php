<?php

namespace App\Commands;

class MakeModel {
    public $controllerName;

    public function handle($controllerName) {
        $this->controllerName = $controllerName;
        $controllerFile = fopen(APP_PATH . "/models/" . $controllerName . ".php", "w") or die("Unable to open file!");

        $controllerContent = $this->getFileContents();
        fwrite($controllerFile, $controllerContent);
        fclose($controllerFile);
    }

    private function getFileContents(): string {
        return <<<EOF
<?php
namespace App\Models;

use App\Lib\Model;

class $this->controllerName extends Model
{
    public string \$table = "";
}
EOF;
    }
}
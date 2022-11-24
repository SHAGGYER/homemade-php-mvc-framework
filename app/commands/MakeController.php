<?php

namespace App\Commands;

class MakeController {
    public $controllerName;

    public function handle($controllerName) {
        $this->controllerName = $controllerName;
        $controllerFile = fopen(APP_PATH . "/controllers/" . $controllerName . ".php", "w") or die("Unable to open file!");

        $controllerContent = $this->getFileContents();
        fwrite($controllerFile, $controllerContent);
        fclose($controllerFile);
    }

    private function getFileContents(): string {
        return <<<EOF
<?php
namespace App\Controllers;

use App\Lib\Controller;

class $this->controllerName extends Controller
{
    public function index()
    {
        echo "Hello World!";
    }
}
EOF;
    }
}
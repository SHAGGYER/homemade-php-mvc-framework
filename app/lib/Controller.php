<?php 

namespace App\Lib;

class Controller {
    public function __construct()
    {
        Request::parseIncoming();
    }

    public function modelsToArray(array $array) {
        $json = [];
        foreach ($array as $row) {
            $jsonRow = [];
            if ($row instanceof Model) {
                foreach ($row->getAttributes() as $key => $value) {
                    $jsonRow[$key] = $value;
                }
            }
      
            $json[] = $jsonRow;
        }

        return $json;
    }

}
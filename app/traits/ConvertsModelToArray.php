<?php

namespace App\Traits;

use App\Lib\Model;

trait ConvertsModelToArray {
    public function toArray() {
        $attrs = $this->getAttributes();

        foreach ($attrs as $key => $value) {
            if ($value instanceof Model) {
                $attrs[$key] = $value->toArray();
            }
        }

        return $attrs;
    }
}
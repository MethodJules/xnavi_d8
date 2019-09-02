<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 03.06.19
 * Time: 13:59
 */

namespace Drupal\xnavi_bi\Data;



class Data
{

    public function getData() {

        $data = [
            'data1' => [30, 200, 100, 400, 150, 250],
            'data2' => [50, 20, 10, 40, 15, 25],
        ];

        return $data;
    }
}
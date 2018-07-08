<?php
/**
 * Created by PhpStorm.
 * User: davidannebicque
 * Date: 08/07/2018
 * Time: 11:54
 */

namespace App\MesClasses;


abstract class Tools
{

    public static function convertDateToObject($date)
    {
        $t = explode('/', $date);
        if (count($t) === 3) {
            $date = $t[2] . '-' . $t[1] . '-' . $t[0];
        }

        return new \DateTime($date);
    }

    public static function convertTimeToObject($heure)
    {


        return new \DateTime($heure);
    }
}
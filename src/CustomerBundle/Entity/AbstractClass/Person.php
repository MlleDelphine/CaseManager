<?php
/**
 * Created by PhpStorm.
 * User: delphine.graftieaux
 * Date: 30/03/2018
 * Time: 14:10
 */

namespace CustomerBundle\Entity\AbstractClass;


abstract class Person extends Customer implements PersonInterface {

    /**
     * @return array
     */
    public static function getAllHonorifics()
    {
        return
            [
                "mister" => "mister",
                "miss" => "miss",
                "madam" => "madam",
            ];
    }

    /**
     * @param string $honorificKey
     * @return mixed
     */
    public static function getHonorificByKey(string $honorificKey){

        $honorifics = self::getAllHonorifics();
        if(array_key_exists($honorificKey, $honorifics)) {
            return $honorifics[$honorificKey];
        }
        return false;
    }

}
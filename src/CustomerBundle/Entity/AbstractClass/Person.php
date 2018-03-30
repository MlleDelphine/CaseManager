<?php
/**
 * Created by PhpStorm.
 * User: delphine.graftieaux
 * Date: 30/03/2018
 * Time: 14:10
 */

namespace CustomerBundle\Entity\AbstractClass;


class Person implements PersonInterface {

    /**
     * @return array
     */
    public function getAllHonorifics()
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
    public function getHonorificByKey(string $honorificKey){

        $honorifics = $this->getAllHonorifics();
        return $honorifics[$honorificKey];
    }

}
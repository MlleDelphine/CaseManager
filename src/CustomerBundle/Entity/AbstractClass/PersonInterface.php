<?php
/**
 * Created by PhpStorm.
 * User: delphine.graftieaux
 * Date: 30/03/2018
 * Time: 14:11
 */

namespace CustomerBundle\Entity\AbstractClass;


interface PersonInterface {

    /**
     * @return array
     */
    public static function getAllHonorifics();

    /**
     * @param string $honorificKey
     * @return string
     */
    public function getHonorificByKey(string $honorificKey);
}
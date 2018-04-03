<?php
/**
 * Created by PhpStorm.
 * User: delphine.graftieaux
 * Date: 30/03/2018
 * Time: 14:11
 */

namespace CustomerBundle\Entity\AbstractClass;


interface CorporationInterface {

    /**
     * @return array
     */
    public static function getAllLegalStatus();

    /**
     * @param string $legalStatusKey
     * @return string
     */
    public function getLegalStatusByKey(string $legalStatusKey);
}
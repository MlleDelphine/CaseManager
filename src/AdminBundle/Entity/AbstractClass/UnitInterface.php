<?php
/**
 * Created by PhpStorm.
 * User: delphine.graftieaux
 * Date: 24/05/2018
 * Time: 15:47
 */

namespace AdminBundle\Entity\AbstractClass;


interface UnitInterface {

    /**
     * @return array
     */
    public static function getAllUnits();

    /**
     * @param string $unitKey
     * @return string
     */
    public static function getUnitByKey(string $unitKey);
}
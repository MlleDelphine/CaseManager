<?php
/**
 * Created by PhpStorm.
 * User: delphine.graftieaux
 * Date: 24/05/2018
 * Time: 15:48
 */

namespace AdminBundle\Entity\AbstractClass;


abstract class Unit implements UnitInterface {

    /**
     * @return array
     */
    public static function getAllUnits(){
        return [
            "Temps" => [
                "Heure" => "Heure",
                "Journée" => "Journée",
                "Semaine" => "Semaine",
                "Mois" => "Mois",
                "Année" => "Année"],
            "Masse" => [
                "Tonne" => "Tonne",
                "kg" => "kg"],
            "Volume" => [
                "m³" => "m³",
                "Litre" => "Litre"],
            "Longueur" => [
                "Mètre linéaire" => "Mètre linéaire",
                "m" => "m"],
            "Superficie" => [
                "m²" => "m²",
                "ha" => "ha"],
            "Autres" => [
                "Benne" => "Benne",
                "Palette" => "Palette",
                "Pièce" => "Pièce",
                "Sac" => "Sac"]
        ];
    }

    /**
     * Retrieve all subUnits array by a mainKey
     *
     * @param string $mainKey
     * @return false|string
     */
    public static function getSubUnitsByMainKey(string $mainKey) {
        $units = self::getAllUnits();
        if(array_key_exists($mainKey, $units)){
            return $units[$mainKey];
        }
        return false;
    }

    /**
     * Merge restricted subUnits by mainKey
     *
     * @param array $mainKeys
     * @return array
     */
    public static function getSubUnitsByMainKeys(array $mainKeys) {
        $units = self::getAllUnits();
        $truncatedSubUnits = [];
        foreach ($mainKeys as $mainKey){
            if (array_key_exists($mainKey, $units)) {
                $truncatedSubUnits[$mainKey] = $units[$mainKey];
            }
        }
        return $truncatedSubUnits;
    }


    /**
     * Retrieve the subUnit string value by subKey
     *
     * @param string $unitSubKey
     * @return false|string
     */
    public static function getSubUnitBySubKey(string $unitSubKey) {
        $units = self::getAllUnits();
        foreach ($units as $mainKey => $subUnits){
            if(array_key_exists($unitSubKey, $subUnits)){
                return $subUnits[$unitSubKey];
            }
        }
        return false;
    }

    /**
     * Retrieve the subKey string key by subUnit string value
     *
     * @param string $unitKey
     * @return false|string
     */
    public static function getSubKeyBySubUnit(string $subUnitValue) {
        $units = self::getAllUnits();
        foreach ($units as $mainKey => $subUnits){
            $subKey = array_search($subUnitValue, $subUnits);
            if($subKey){
                return $subKey;
            }
        }
        return $subKey;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: delphine.graftieaux
 * Date: 30/03/2018
 * Time: 14:10
 */

namespace CustomerBundle\Entity\AbstractClass;


class Corporation implements CorporationInterface {

    /**
     * @return array
     */

    /**
     * @return array
     */
    public static function getAllLegalStatus(){

        return
            [
                "corporation_legal_status_ei" => "EI",
                "corporation_legal_status_eirl" => "EIRL",
                "corporation_legal_status_eurl" => "EURL",
                "corporation_legal_status_sa" => "SA",
                "corporation_legal_status_sarl" => "SARL",
                "corporation_legal_status_sas" => "SAS",
                "corporation_legal_status_sasu" => "SASU",
                "corporation_legal_status_sca" => "SCA",
                "corporation_legal_status_sci" => "SCI",
                "corporation_legal_status_scp" => "SCP",
                "corporation_legal_status_scs" => "SCS",
                "corporation_legal_status_sel" => "SEL",
                "corporation_legal_status_snc" => "SNC",
                "corporation_legal_status_other" => "N/P"
            ];
    }

    /**
     * @param string $legalStatusKey
     * @return mixed
     */
    public function getLegalStatusByKey(string $legalStatusKey){

        $legalStatuses = $this->getAllLegalStatus();
        return $legalStatuses[$legalStatusKey];
    }

    public function getObjectName() {
        return "Corporation";
    }
}
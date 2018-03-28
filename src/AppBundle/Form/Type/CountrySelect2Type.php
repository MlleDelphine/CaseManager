<?php
/**
 * Created by PhpStorm.
 * User: delphine.graftieaux
 * Date: 28/03/2018
 * Time: 15:15
 */

namespace AppBundle\Form\Type;


use Symfony\Component\Form\Extension\Core\Type\CountryType;

class CountrySelect2Type extends CountryType {

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'AppBundle\Form\Type\Select2ChoiceType';
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: BDHK6353
 * Date: 07/04/2017
 * Time: 10:31
 */

namespace AppBundle\Form\Type;


use Genemu\Bundle\FormBundle\Form\JQuery\Type\Select2Type;

/**
 * Select2ChoiceType
 */
class Select2ChoiceType extends Select2Type
{
    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct('choice');
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 28/11/2018
 * Time: 17:19
 */

namespace AppBundle\Services;


use APY\DataGridBundle\Grid\Column\Column;

class ColorTagGridColumn extends Column
{
    public function __initialize(array $params)
    {
        parent::__initialize($params); // TODO: Change the autogenerated stub
    }

    public function getType()
    {
        return "color_tag"; // TODO: Change the autogenerated stub
    }
}
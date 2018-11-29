<?php
/**
 * Created by PhpStorm.
 * User: BDHK6353
 * Date: 06/02/2017
 * Time: 15:20
 */

namespace AppBundle\services;


use APY\DataGridBundle\Grid\Export\Export;
use APY\DataGridBundle\Grid\Export\Grid;

class PDFExport extends Export
{

    /**
     * function call by the grid to fill the content of the export
     *
     * @param Grid $grid The grid
     */
    public function computeData($grid)
    {
        // TODO: Implement computeData() method.
    }
}
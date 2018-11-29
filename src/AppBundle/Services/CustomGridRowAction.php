<?php
/**
 * Created by PhpStorm.
 * User: Delphine
 * Date: 27/11/2018
 * Time: 19:18
 */

namespace AppBundle\Services;

use APY\DataGridBundle\Grid\Action\RowAction;

class CustomGridRowAction extends RowAction
{
    protected $prevIcon;

    protected $isSubRow = false;

    public function __construct(string $title, string $route, bool $confirm = false, string $target = '_self', array $attributes = [], string $role = null)
    {
        parent::__construct($title, $route, $confirm, $target, $attributes, $role);
    }

    /**
     * @return mixed
     */
    public function getPrevIcon()
    {
        return $this->prevIcon;
    }

    /**
     * @param mixed $prevIcon
     */
    public function setPrevIcon($prevIcon)
    {
        $this->prevIcon = $prevIcon;
    }

    /**
     * @return bool
     */
    public function isSubRow(): bool
    {
        return $this->isSubRow;
    }

    /**
     * @param bool $isSubRow
     */
    public function setIsSubRow(bool $isSubRow)
    {
        $this->isSubRow = $isSubRow;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: BDHK6353
 * Date: 06/02/2017
 * Time: 10:21
 */

namespace AppBundle\Services;


use APY\DataGridBundle\Grid\Export\Export;
//use APY\DataGridBundle\Grid\Export

class ExcelExport extends Export
{
    protected $fileExtension = 'xls';

    protected $mimeType = 'application/vnd.ms-excel';

    public $objPHPExcel;

    public function __construct($title, $fileName = 'export', $params = array(), $charset = 'UTF-8')
    {
        $this->objPHPExcel =  new \PHPExcel();

        parent::__construct($title, $fileName, $params, $charset);
    }

    public function computeData($grid)
    {
        $data = $this->getFlatGridData($grid);
        $lastColumnLetter = chr(ord('A') + count($data[0]) - 1);
        $row = 1;
        foreach ($data as $line) {
            $column = 'A';
            $columnRange = 0;

            foreach ($line as $cell) {
                $this->objPHPExcel->getActiveSheet()->SetCellValue($column.$row, $cell);
                $this->objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($columnRange)->setAutoSize(true);

                $column++;
                $columnRange++;
            }
            $row++;
        }
        //Coloring titles
        $lastColumn= $lastColumnLetter."1";
        $this->objPHPExcel->getActiveSheet()
            ->getStyle("A1:$lastColumn")
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('CDBFE3');

        //Setting borders
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $lastRow = $row-1;
        $rangeArray = "A1:".$lastColumnLetter.$lastRow;
        $this->objPHPExcel->getActiveSheet()->getStyle($rangeArray)->applyFromArray($styleArray);
        unset($styleArray);

        $objWriter = $this->getWriter();

        ob_start();

        $objWriter->save("php://output");

        $this->content = ob_get_contents();

        ob_end_clean();
    }

    protected function getWriter()
    {
        return new \PHPExcel_Writer_Excel5($this->objPHPExcel);
    }
}
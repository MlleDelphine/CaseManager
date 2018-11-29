<?php
/**
 * Created by PhpStorm.
 * User: BDHK6353
 * Date: 02/02/2017
 * Time: 17:39
 */

namespace AppBundle\Services;


use APY\DataGridBundle\Grid\Export\Export;

class CSVExport extends Export
{
    protected $fileExtension = 'csv';

    protected $mimeType = 'text/csv'; //comma-separated-values

    public function computeData($grid)
    {
//        $data = $this->getGridData($grid); // Titles and rows arrays. Titles array in associative. Row array contains row line as array.
//        $data0 = $this->getRawGridData($grid); // Titles and rows arrays. Ttiles array is indexed array. Row array contains row line as object.
          $data = $this->getFlatGridData($grid); //One array, first line = titles. Associative for titles array. "string" for rows array.
//        $data2 = $this->getFlatRawGridData($grid); //One array, first line = titles. Indexed for titles array. "object" for rows array.
//        $data3 = $this->getGridTitles($grid); //Associative for titles array.
//        $data4 = $this->getRawGridTitles($grid); //Indexed for titles array.
//        $data5 = $this->getGridRows($grid); //"string" for rows array. enabled => ""
//        $data6 = $this->getRawGridRows($grid); //"object" for rows array. enabled => true

        // Array to dsv
        $outstream = fopen("php://temp", 'r+');

        foreach ($data as $line){

            fputcsv($outstream, $line, ';', '"');
        }

        rewind($outstream);

        $content = '';
        while (($buffer = fgets($outstream)) !== false) {
            $content .= $buffer;
        }

        fclose($outstream);

        $this->content = $content;
    }
}
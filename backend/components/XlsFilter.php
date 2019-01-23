<?php
namespace backend\components;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class XlsFilter implements IReadFilter
{
    private $startRow = 0;
    private $columns = [];

    public function __construct($startRow, $columns)
    {
        $this->startRow = $startRow;
        $this->columns = $columns;
    }

    public function readCell($column, $row, $worksheetName = '')
    {

        if ($row >= $this->startRow) {
            if (in_array($column, $this->columns)) {
                return true;
            }
        }

        return false;
    }


}
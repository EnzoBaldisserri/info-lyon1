<?php

namespace App\Service\Spreadsheet;


use App\Helper\SpreadsheetHelper;
use App\Service\SpreadsheetService;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class BaseEntityReader implements IEntityReader
{
    protected $service;
    protected $helper;

    public function __construct(SpreadsheetService $spreadsheetService, SpreadsheetHelper $spreadsheetHelper)
    {
        $this->service = $spreadsheetService;
        $this->helper = $spreadsheetHelper;
    }

    /**
     * @inheritdoc
     */
    public function read($entity, Worksheet $worksheet)
    {
        if (is_string($entity)) {
            $entity = new $entity();
        }

        $row = $this->readHeader($entity, $worksheet);
        $row = $this->readContent($entity, $worksheet, $row);
        $this->readFooter($entity, $worksheet, $row);

        return $entity;
    }

    /**
     * Reads the header of the worksheet.<br>
     * In most cases, this should just skip the header
     * by returning the line at which starts the content.
     *
     * @param object $entity
     * @param Worksheet $worksheet
     * @return int                  The next row to be read
     */
    protected function readHeader(&$entity, Worksheet $worksheet): int
    {
        return 1;
    }

    /**
     * Reads the information about the entity.
     *
     * @param object $entity
     * @param Worksheet $worksheet
     * @param int $row              The first row to be read
     * @return int                  The next row to be read
     */
    protected abstract function readContent(&$entity, Worksheet $worksheet, int $row): int;

    /**
     * Reads the footer of a spreadsheet.<br>
     * In most cases, this shouldn't do anything.
     *
     * @param object $entity
     * @param Worksheet $worksheet
     * @param int $row              The first row to be read
     */
    protected function readFooter(&$entity, Worksheet $worksheet, int $row): void
    {
    }
}

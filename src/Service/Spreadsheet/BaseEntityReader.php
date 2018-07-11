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

        $row = $this->readHeader($worksheet);
        $row = $this->readContent($entity, $worksheet, $row);
        $this->readFooter($worksheet, $row);

        return $entity;
    }

    /**
     * @inheritdoc
     */
    public function readHeader(Worksheet $worksheet): int
    {
        return 1;
    }

    /**
     * @inheritdoc
     */
    public abstract function readContent(&$entity, Worksheet $worksheet, int $row): int;

    /**
     * @inheritdoc
     */
    public function readFooter(Worksheet $worksheet, int $row): void
    {
    }
}

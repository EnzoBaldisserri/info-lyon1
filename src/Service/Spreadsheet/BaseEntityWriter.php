<?php

namespace App\Service\Spreadsheet;


use App\Helper\SpreadsheetHelper;
use App\Service\SpreadsheetService;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\Translation\TranslatorInterface;

abstract class BaseEntityWriter implements IEntityWriter
{
    protected $service;
    protected $helper;
    protected $translator;

    public function __construct(
        SpreadsheetService $spreadsheetService,
        SpreadsheetHelper $spreadsheetHelper,
        TranslatorInterface $translator
    ) {
        $this->service = $spreadsheetService;
        $this->helper = $spreadsheetHelper;
        $this->translator = $translator;
    }

    /**
     * @inheritdoc
     */
    public function write($entity, Worksheet $worksheet): Worksheet
    {
        $row = $this->writeHeader($entity, $worksheet);
        $row = $this->writeContent($entity, $worksheet, $row);
        $this->writeFooter($entity, $worksheet, $row);

        return $worksheet;
    }

    /**
     * Writes the header for a type of entity.<br>
     * The header should not contain entity information.
     *
     * @param object    $entity
     * @param Worksheet $worksheet
     * @return int                  The next row to be written to
     */
    protected function writeHeader($entity, Worksheet $worksheet): int
    {
        return 1;
    }

    /**
     * Writes the information about the entity.
     *
     * @param object $entity
     * @param Worksheet $worksheet
     * @param int $row              The first row to be written to
     * @return int                  The next row to be written to
     */
    protected abstract function writeContent($entity, Worksheet $worksheet, int $row): int;

    /**
     * Writes the footer for a type of entity.
     * The footer should not contain entity information.
     *
     * @param object $entity
     * @param Worksheet $worksheet
     * @param int $row              The first row to be written to
     */
    protected function writeFooter($entity, Worksheet $worksheet, int $row): void
    {
    }

}

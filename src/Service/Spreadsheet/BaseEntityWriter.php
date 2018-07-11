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
        $row = $this->writeHeader($worksheet);
        $row = $this->writeContent($entity, $worksheet, $row);
        $this->writeFooter($worksheet, $row);

        return $worksheet;
    }

    /**
     * @inheritdoc
     */
    public function writeHeader(Worksheet $worksheet): int
    {
        return 1;
    }

    /**
     * @inheritdoc
     */
    public abstract function writeContent($entity, Worksheet $worksheet, int $row): int;

    /**
     * @inheritdoc
     */
    public function writeFooter(Worksheet $worksheet, int $row): void
    {
    }

}

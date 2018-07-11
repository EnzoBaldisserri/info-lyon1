<?php

namespace App\Service\Spreadsheet;


use App\Helper\SpreadsheetHelper;
use App\Service\SpreadsheetService;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\Translation\TranslatorInterface;

interface IEntityWriter
{
    public function __construct(
        SpreadsheetService $spreadsheetService,
        SpreadsheetHelper $spreadsheetHelper,
        TranslatorInterface $translator
    );

    /**
     * Writes a whole entity to a spreadsheet.
     * Should mainly consist on calling the following methods:
     * <ol>
     *  <li>writeHeader</li>
     *  <li>writeContent</li>
     *  <li>writeFooter</li>
     * </ol>
     *
     * @param object $entity
     * @param Worksheet $worksheet
     * @return Worksheet            The updated worksheet
     */
    public function write($entity, Worksheet $worksheet): Worksheet;

    /**
     * Writes the header for a type of entity.
     * In most cases, the header should not contain information about the entity.
     *
     * @param Worksheet $worksheet
     * @return int                  The next row to be written to
     */
    public function writeHeader(Worksheet $worksheet): int;

    /**
     * Writes the information about the entity.
     *
     * @param object $entity
     * @param Worksheet $worksheet
     * @param int $row              The first row to be written to
     * @return int                  The next row to be written to
     */
    public function writeContent($entity, Worksheet $worksheet, int $row): int;

    /**
     * Writes the footer for a type of entity.
     *
     * @param Worksheet $worksheet  The worksheet
     * @param int $row              The first row to be written to
     */
    public function writeFooter(Worksheet $worksheet, int $row): void;
}

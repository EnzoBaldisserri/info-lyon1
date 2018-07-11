<?php

namespace App\Service\Spreadsheet;


use App\Helper\SpreadsheetHelper;
use App\Service\SpreadsheetService;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

interface IEntityReader
{
    public function __construct(SpreadsheetService $spreadsheetService, SpreadsheetHelper $spreadsheetHelper);

    /**
     * Updates an entity properties base on a spreadsheet.
     * Should mainly consist on calling the following methods:
     * <ol>
     *  <li>readHeader</li>
     *  <li>readContent</li>
     *  <li>readFooter</li>
     * </ol>
     *
     * @param object|string $entity     The entity or it's class name
     * @param Worksheet $worksheet      The worksheet
     * @return object                   The updated entity
     */
    public function read($entity, Worksheet $worksheet);

    /**
     * Reads the header of the worksheet.
     * In most cases, this should just skip the header
     * by returning the line of the beginning of the content.
     *
     * @param Worksheet $worksheet  The spreadsheet
     * @return int                  The next row to be read
     */
    public function readHeader(Worksheet $worksheet): int;

    /**
     * Reads the information about the entity.
     *
     * @param object $entity        The entity
     * @param Worksheet $worksheet  The worksheet
     * @param int $row              The first row to be read
     * @return int                  The next row to be read
     */
    public function readContent(&$entity, Worksheet $worksheet, int $row): int;

    /**
     * Reads the footer of a spreadsheet.
     * In most cases, should not do anything.
     *
     * @param Worksheet $worksheet  The worksheet
     * @param int $row              The first row to be read
     */
    public function readFooter(Worksheet $worksheet, int $row): void;
}

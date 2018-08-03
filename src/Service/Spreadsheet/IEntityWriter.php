<?php

namespace App\Service\Spreadsheet;


use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

interface IEntityWriter
{
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
}

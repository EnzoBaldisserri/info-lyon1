<?php

namespace App\Service\Spreadsheet;


use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

interface IEntityReader
{
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
}

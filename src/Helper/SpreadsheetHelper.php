<?php

namespace App\Helper;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Exception as SpreadsheetException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as ReadException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Exception as WriteException;
use SplFileInfo;

class SpreadsheetHelper
{
    /**
     * Parse the file to a spreadsheet.
     * It can be an Xls, Xlsx, Xml, Ods, Slk, Gnumeric, Html or Csv file.
     *
     * @param SplFileInfo $file The file to read
     * @return Spreadsheet The spreadsheet
     *
     * @throws ReadException If the file is not a file or is not readable
     */
    public function read(SplFileInfo $file): Spreadsheet
    {
        if (!$file->isFile()) {
            throw new ReadException(sprintf('"%s" is not a file', $file->getPathname()));
        }

        if (!$file->isReadable()) {
            throw new ReadException(sprintf('"%s" is not readable', $file->getPathname()));
        }

        return IOFactory::load($file->getRealPath());
    }

    /**
     * Write a spreadsheet to a file. Type is guessed on file extension.
     * It can be Xls, Xlsx, Ods, Csv or Html.
     *
     * @param SplFileInfo $file
     * @param Spreadsheet $spreadsheet
     * @param bool $force Whether to force creation if a file exists or not
     *
     * @throws WriteException If file already exists and creation is not forced
     */
    public function write(SplFileInfo $file, Spreadsheet $spreadsheet, bool $force = false): void
    {
        $writerType = ucfirst($file->getExtension());
        $writer = IOFactory::createWriter($spreadsheet, $writerType);

        if (!$force && file_exists($file->getPathname())) {
            throw new WriteException(sprintf('"%s" already exists', $file->getPathname()));
        };

        $writer->save($file->getPathname());
    }

    /**
     * Apply a style from predefined set.
     * The style are based on Excel's cell's style.
     * <ul>
     *  <li>bad</li>
     *  <li>good</li>
     * </ul>
     *
     * @param Cell $cell
     * @param string $style
     *
     * @throws SpreadsheetException If style does not exists
     */
    public function setCellStyle(Cell $cell, string $style)
    {
        static $cellStyles = [
            'bad' => [
                'fill' => [
                    'fillType' => Fill::FILL_GRADIENT_LINEAR,
                    'color' => ['argb' => 'FFFFC7CE'],
                ],
                'font' => [
                    'color' => ['argb' => 'FF9C0006'],
                ],
            ],
            'good' => [
                'fill' => [
                    'color' => ['argb' => 'FFC6EFCE'],
                ],
                'font' => [
                    'color' => ['argb' => 'FF006100'],
                ],
            ],
        ];

        $style = strtolower($style);

        if (!isset($cellStyles[$style])) {
            throw new SpreadsheetException(sprintf('Style "%s" does not exist', $style));
        }

        $cell->getStyle()->applyFromArray($cellStyles[$style]);
    }
}

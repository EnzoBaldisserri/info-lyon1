<?php

namespace App\Helper;

use App\Entity\Administration\Semester;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Exception as WriteException;
use PhpOffice\PhpSpreadsheet\Reader\Exception as ReadException;
use SplFileInfo;

class SpreadsheetHelper
{
    /**
     * Parse the file to a spreadsheet.
     * It can be an Xls, Xlsx, Xml, Ods, Slk, Gnumeric, Html or Csv file.
     *
     * @param SplFileInfo $file The file to read
     * @return Spreadsheet The spreadsheet
     * @throws ReadException
     */
    public function read(SplFileInfo $file): Spreadsheet
    {
        if (!$file->isFile()) {
            throw new ReadException('Not a real file');
        }

        if (!$file->isReadable()) {
            throw new ReadException('Unreadable file');
        }

        return IOFactory::load($file->getRealPath());
    }

    /**
     * Write a spreadsheet to a file. Type is guessed on file extension.
     * It can be xls, xlsx, ods, csv or html.
     *
     * @param SplFileInfo $file
     * @param Spreadsheet $spreadsheet
     * @param bool $force Whether to force creation if a file exists or not
     * @throws WriteException
     */
    public function write(SplFileInfo $file, Spreadsheet $spreadsheet, bool $force = false): void
    {
        $writerType = ucfirst($file->getExtension());
        $writer = IOFactory::createWriter($spreadsheet, $writerType);

        if (!$force && file_exists($file->getPathname())) {
            throw new WriteException('Already existing file');
        };

        if (!$file->isWritable()) {
            throw new WriteException('File not writable');
        }

        $writer->save($file->getRealPath());
    }

    /**
     * Create a spreadsheet representing the semester.
     *
     * @param Semester $semester The semester
     * @return Spreadsheet The spreadsheet
     */
    public function createForSemester(Semester $semester): Spreadsheet
    {

    }

    public function createForSemesterSample(): Spreadsheet
    {

    }

    /**
     * Update a semester based on the spreadsheet.
     * If the spreadsheet doesn't respect the necessary format, function will fail and return false.
     *
     * @param Spreadsheet $spreadsheet The spreadsheet
     * @param Semester $semester The semester to be modified
     * @throws ReadException
     */
    public function updateSemester(Spreadsheet $spreadsheet, Semester &$semester): void
    {

    }

    /**
     * Create a semester based on the spreadsheet.
     * If the spreadsheet doesn't respect the necessary format, function will fail and return null.
     *
     * @param Spreadsheet $spreadsheet
     * @return Semester|null
     * @throws ReadException
     */
    public function createSemester(Spreadsheet $spreadsheet): Semester
    {
        $semester = new Semester();
        $this->updateSemester($spreadsheet, $semester);

        return $semester;
    }
}

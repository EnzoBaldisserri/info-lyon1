<?php

namespace App\Service;


use App\Helper\SpreadsheetHelper;
use App\Entity;
use App\Service\Spreadsheet as Assets;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use SplFileInfo;
use Symfony\Component\Translation\TranslatorInterface;

class SpreadsheetService
{
    const registeredWriters = [
        Entity\Administration\Semester::class => Assets\SemesterWriter::class,
    ];

    const registeredReaders = [
        Entity\Administration\Semester::class => Assets\SemesterReader::class,
    ];

    protected $spreadsheetHelper;
    protected $translator;

    public function __construct(SpreadsheetHelper $spreadsheetHelper, TranslatorInterface $translator)
    {
        $this->spreadsheetHelper = $spreadsheetHelper;
        $this->translator = $translator;
    }

    /**
     * Writes an entity to a file.
     *
     * @param object $entity
     * @param SplFileInfo $file
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function writeEntity($entity, SplFileInfo $file): void
    {
        $spreadsheet = $this->writeEntityToSpreadsheet($entity, new Spreadsheet());

        $this->spreadsheetHelper->write($file, $spreadsheet, true);
    }

    /**
     * Writes an entity to the active sheet of a spreadsheet.
     *
     * @param object $entity
     * @param Spreadsheet $spreadsheet
     * @return Spreadsheet              The updated spreadsheet
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function writeEntityToSpreadsheet($entity, Spreadsheet $spreadsheet): Spreadsheet
    {
        $entityClass = get_class($entity);
        if (!isset(self::registeredWriters[$entityClass])) {
            throw new Exception('There is no writer associated with this entity');
        }

        $writerClass = self::registeredWriters[$entityClass];

        /** @var Assets\IEntityWriter $writer */
        $writer = new $writerClass($this, $this->spreadsheetHelper, $this->translator);
        $writer->write($entity, $spreadsheet->getActiveSheet());

        return $spreadsheet;
    }

    /**
     * Reads an entity from a file.
     *
     * @param object|string $entity  The entity on updating, or it's fully qualified class name when creating
     * @param SplFileInfo $file
     * @return object                The created  or updated entity
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function readEntity($entity, SplFileInfo $file)
    {
        $spreadsheet = $this->spreadsheetHelper->read($file);

        return $this->readEntityFromSpreadsheet($entity, $spreadsheet);
    }

    /**
     * Read entity from the active sheet of a worksheet.
     *
     * @param string|object $entity      The entity on updating, or it's fully qualified class name when creating
     * @param Spreadsheet $spreadsheet
     * @return object                    The created or updated entity
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function readEntityFromSpreadsheet($entity, Spreadsheet $spreadsheet)
    {
        $entityClass = is_string($entity) ? $entity : get_class($entity);

        if (!isset(self::registeredReaders[$entityClass])) {
            throw new Exception('No reader associated with this class');
        }

        $readerClass = self::registeredReaders[$entityClass];

        /** @var Assets\IEntityReader $reader */
        $reader = new $readerClass($this, $this->spreadsheetHelper);

        $entity = $reader->read($entity, $spreadsheet->getActiveSheet());

        return $entity;
    }
}

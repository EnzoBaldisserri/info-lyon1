<?php

namespace App\Helper;

use App\Entity\Administration\Group;
use App\Entity\Administration\Semester;
use App\Entity\User\Student;
use App\Repository\Administration\CourseRepository;
use App\Repository\Administration\SemesterRepository;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Symfony\Component\Translation\TranslatorInterface;
use Doctrine\Common\Collections\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Exception as SpreadsheetException;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Exception as WriteException;
use PhpOffice\PhpSpreadsheet\Reader\Exception as ReadException;
use SplFileInfo;

class SpreadsheetHelper
{
    private $translator;
    private $courseRepository;
    private $semesterRepository;

    public function __construct(TranslatorInterface $translator, CourseRepository $courseRepository, SemesterRepository $semesterRepository)
    {
        $this->translator = $translator;
        $this->courseRepository = $courseRepository;
        $this->semesterRepository = $semesterRepository;
    }

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
     *
     * @throws SpreadsheetException
     */
    public function createForSemester(Semester $semester): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();

        $worksheet = $spreadsheet->getActiveSheet();

        $this->writeHeader($worksheet);
        $this->writeProperties($worksheet, $semester);
        $this->writeFooter($worksheet);

        // Groups
        return $spreadsheet;
    }

    /**
     * Create a sample spreadsheet.
     *
     * @return Spreadsheet
     *
     * @throws SpreadsheetException
     */
    public function createForSemesterSample(): Spreadsheet
    {
        $nextPeriod = $this->semesterRepository->findNextPeriod();

        $lastBeginningCourse = $this->courseRepository->findOneBy(
            [ 'semester' => 1 ],
            [ 'id' => 'DESC']
        );

        /** @var Student $sampleStudent1 */
        $sampleStudent1 = (new Student())
            ->setUsername('p0000001')
            ->setFirstname('John')
            ->setSurname('Doe');

        /** @var Student $sampleStudent2 */
        $sampleStudent2 = (new Student())
            ->setUsername('p0000012')
            ->setFirstname('Richard')
            ->setSurname('Roe');

        $sampleGroup1 = (new Group())
            ->setNumber(1)
            ->addStudent($sampleStudent1)
            ->addStudent($sampleStudent2)
        ;

        /** @var Student $sampleStudent3 */
        $sampleStudent3 = (new Student)
            ->setUsername('p0000008')
            ->setFirstname('Jane')
            ->setSurname('Doe');

        $sampleGroup2 = (new Group())
            ->setNumber(2)
            ->addStudent($sampleStudent3)
        ;

        $sampleSemester = (new Semester())
            ->setPeriod($nextPeriod)
            ->setCourse($lastBeginningCourse)
            ->addGroup($sampleGroup1)
            ->addGroup($sampleGroup2)
        ;

        return $this->createForSemester($sampleSemester);
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

    /**
     * @param Cell $cell
     * @param string $style
     *
     * @throws SpreadsheetException
     */
    protected function setCellStyle(Cell $cell, string $style)
    {
        static $styles = [
            'bad' => [
                'fill' => [
                    'color' => ['argb' => 'FF9C0006'],
                ],
                'font' => [
                    'color' => ['argb' => 'FFFFC7CE'],
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

        if (!isset($styles[$style])) {
            throw new SpreadsheetException('Style does not exist');
        }

        $cell->getStyle()->applyFromArray($styles[$style]);
    }

    protected function writeHeader(Worksheet $worksheet): void
    {
        $worksheet->setCellValue(
            'A1', $this->translator->trans('semester.file.title.property'));
        $worksheet->setCellValue(
            'B1', $this->translator->trans('semester.file.title.value'));
        $worksheet->setCellValue(
            'C1', $this->translator->trans('semester.file.title.more_infos'));
    }

    /**
     * @param Worksheet $worksheet
     * @param Semester $semester
     *
     * @throws SpreadsheetException
     */
    protected  function writeProperties(Worksheet $worksheet, Semester $semester): void
    {
        // id
        $worksheet->setCellValue(
            'A2', 'id');
        $cell = $worksheet->getCell('B2');
        $cell->setValue($semester->getId() ?? '');
        $this->setCellStyle($cell, 'bad');

        // Course type
        $worksheet->setCellValue(
            'A3', $this->translator->trans('course.props.semester'));
        $worksheet->setCellValue(
            'B3', $semester->getCourse()->getSemester());

        // Course PPN
        $worksheet->setCellValue(
            'A4', $this->translator->trans('course.props.implementationYear'));
        $worksheet->setCellValue(
            'B4', $semester->getCourse()->getImplementationDate()->format('Y'));

        // Start date
        $worksheet->setCellValue(
            'A5', $this->translator->trans('semester.props.start_date'));
        $worksheet->setCellValue(
            'B5', $semester->getStartDate()->format('Y-m-d'));

        // End date
        $worksheet->setCellValue(
            'A6', $this->translator->trans('semester.props.end_date'));
        $worksheet->setCellValue(
            'B6', $semester->getEndDate()->format('Y-m-d'));

        // Groups
        $this->writeGroups($worksheet, $semester->getGroups());
    }

    /**
     * @param Worksheet $worksheet
     * @param Group[]|Collection $groups
     *
     * @throws SpreadsheetException
     */
    private function writeGroups(Worksheet $worksheet, Collection $groups): void
    {
        $row = $worksheet->getHighestRow() + 2;

        foreach ($groups as $group) {
            // Group name
            $worksheet->setCellValue(
                'A'.$row,
                $this->translator->trans('group.entity', [
                    '%number%' => $group->getNumber(),
                ])
            );

            $row += 1;

            // id
            $worksheet->setCellValue(
                'A'.$row, 'id');
            $cell = $worksheet->getCell('B'.$row);
            $cell->setValue($group->getId() ?? '');
            $this->setCellStyle($cell, 'bad');

            $row += 1;

            // Students
            $worksheet->setCellValue(
                'A'.$row, $this->translator->trans('group.props.students'));

            foreach ($group->getStudents() as $student) {
                $worksheet->setCellValue(
                    'B'.$row, $student->getUsername());
                $worksheet->setCellValue(
                    'C'.$row, $student->getFirstname());
                $worksheet->setCellValue(
                    'D'.$row, $student->getSurname());

                $row += 1;
            }

            $row += 2;
        }
    }

    /**
     * @param Worksheet $worksheet
     * @throws SpreadsheetException
     */
    protected  function writeFooter(Worksheet $worksheet): void
    {
        $row = $worksheet->getHighestRow() + 2;

        $cell = $worksheet->getCell('A'.$row);
        $cell->setValue($this->translator->trans('semester.file.footer.no_modify'));
        $this->setCellStyle($cell, 'bad');

        $row += 1;

        $worksheet->setCellValue(
            'A'.$row, $this->translator->trans('semester.file.footer.analyse_value'));
    }
}

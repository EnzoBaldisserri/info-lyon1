<?php

namespace App\Service\Spreadsheet;


use App\Entity\Administration\Group;
use App\Entity\Administration\Semester;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SemesterWriter extends BaseEntityWriter
{
    /**
     * @inheritdoc
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function writeHeader($semester, Worksheet $worksheet): int
    {
        // Style
        $worksheet->getDefaultColumnDimension()->setWidth(14);

        // Warning message
        $worksheet->mergeCells('A1:D1');
        $cell = $worksheet->getCell('A1');
        $cell->setValue($this->translator->trans('semester.file.message.no_modify'));
        $this->helper->setCellStyle($cell, 'bad');

        // Column titles
        $worksheet->setCellValue(
            'A3', $this->translator->trans('semester.file.title.property'));
        $worksheet->setCellValue(
            'B3', $this->translator->trans('semester.file.title.value'));
        $worksheet->setCellValue(
            'C3', $this->translator->trans('semester.file.title.more_info'));
        $worksheet->mergeCells('C3:D3');

        return 4;
    }

    /**
     * @inheritdoc
     *
     * @param Semester $semester
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function writeContent($semester, Worksheet $worksheet, int $row): int
    {
        // Course's type
        $worksheet->setCellValue(
            'A'.$row, $this->translator->trans('course.props.type'));
        $worksheet->setCellValue(
            'B'.$row, $semester->getCourse()->getType());

        $row += 1;

        // Course's implementation year
        $worksheet->setCellValue(
            'A'.$row, $this->translator->trans('course.props.implementationYear'));
        $worksheet->setCellValue(
            'B'.$row, $semester->getCourse()->getImplementationDate()->format('Y'));

        $row += 1;

        // Start date
        $worksheet->setCellValue(
            'A'.$row, $this->translator->trans('semester.props.start_date'));
        $worksheet->setCellValue(
            'B'.$row, Date::PHPToExcel($semester->getStartDate()));
        $worksheet->getStyle('B'.$row)
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);

        $row += 1;

        // End date
        $worksheet->setCellValue(
            'A'.$row, $this->translator->trans('semester.props.end_date'));
        $worksheet->setCellValue(
            'B'.$row, Date::PHPToExcel($semester->getEndDate()));
        $worksheet->getStyle('B'.$row)
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);

        $row += 1;

        // Groups
        foreach ($semester->getGroups() as $group) {
            $row = $this->writeGroup($worksheet, $group, $row);

            $row += 1;
        }

        return $row;
    }

    /**
     * Write semesters's groups
     *
     * @param Worksheet $worksheet
     * @param Group     $group
     * @param int       $row        The first row to be written to
     * @return int                  The next row to be written
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function writeGroup(Worksheet $worksheet, Group $group, int $row): int
    {
        // Name + id
        $worksheet->setCellValue(
            'A'.$row,
            $this->translator->trans('group.entity', [
                '%number%' => $group->getNumber(),
            ])
        );

        $cell = $worksheet->getCell('B'.$row);
        $cell->setValue($group->getId() ?? '');
        $this->helper->setCellStyle($cell, 'bad');

        $row += 1;

        // Don't keep group's number

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

        return $row;
    }
}

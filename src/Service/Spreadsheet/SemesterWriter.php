<?php

namespace App\Service\Spreadsheet;


use App\Entity\Administration\Group;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
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
    public function writeHeader(Worksheet $worksheet): int
    {
        // Style
        $worksheet->getDefaultColumnDimension()->setWidth(14);

        // Actual header
        $worksheet->setCellValue(
            'A1', $this->translator->trans('semester.file.title.property'));
        $worksheet->setCellValue(
            'B1', $this->translator->trans('semester.file.title.value'));
        $worksheet->setCellValue(
            'C1', $this->translator->trans('semester.file.title.more_info'));
        $worksheet->mergeCells('C1:D1');

        return 2;
    }

    /**
     * @inheritdoc
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function writeContent($semester, Worksheet $worksheet, int $row): int
    {
        // id
        $worksheet->setCellValue(
            'A'.$row, 'id');
        $cell = $worksheet->getCell('B'.$row);
        $cell->setValue($semester->getId() ?? '');
        $this->helper->setCellStyle($cell, 'bad');

        $row += 1;

        // Course's type
        $worksheet->setCellValue(
            'A'.$row, $this->translator->trans('course.props.semester'));
        $worksheet->setCellValue(
            'B'.$row, $semester->getCourse()->getSemester());

        $row += 1;

        // Course's implementation date
        $worksheet->setCellValue(
            'A'.$row, $this->translator->trans('course.props.implementationDate'));
        $worksheet->setCellValue(
            'B'.$row, Date::PHPToExcel($semester->getCourse()->getImplementationDate()));
        $worksheet->getStyle('B'.$row)
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);

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

    /**
     * @inheritdoc
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception;
     */
    public function writeFooter(Worksheet $worksheet, int $row): void
    {
        $worksheet->mergeCells("A$row:D$row");
        $cell = $worksheet->getCell('A'.$row);
        $cell->setValue($this->translator->trans('semester.file.footer.no_modify'));
        $this->helper->setCellStyle($cell, 'bad');

        $row += 1;

        $worksheet->mergeCells("A$row:D$row");
        $worksheet->setCellValue(
            'A'.$row, $this->translator->trans('semester.file.footer.analyse_value'));
    }

}

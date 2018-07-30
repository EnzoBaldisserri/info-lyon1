<?php

namespace App\Service\Spreadsheet;

use App\Entity\Administration\Course;
use App\Entity\Administration\Group;
use App\Entity\Administration\Semester;
use App\Entity\User\Student;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SemesterReader extends BaseEntityReader
{
    public function readHeader(&$semester, Worksheet $worksheet): int
    {
        return 4; // Skip header
    }

    /**
     * Reads the semester's information.
     *
     * @param Semester $semester
     * @param Worksheet $worksheet
     * @param int $row              The first
     * @return int
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \Exception
     */
    public function readContent(&$semester, Worksheet $worksheet, int $row): int
    {
        $course = new Course();
        $course->setSemester(
            (int) $worksheet->getCell('B'.$row)->getValue());

        $row += 1;

        $course->setImplementationDate(
            \DateTime::createFromFormat('Y', $worksheet->getCell('B'.$row)->getValue()));

        $semester->setCourse($course);

        $row += 1;

        $semester->setStartDate(
            Date::excelToDateTimeObject($worksheet->getCell('B'.$row)->getValue()));

        $row += 1;

        $semester->setEndDate(
            Date::excelToDateTimeObject($worksheet->getCell('B'.$row)->getValue()));

        $row += 1;

        $row = $this->updateGroups($worksheet, $semester, $row);

        return $row;
    }

    /**
     * Update the collection of group.
     *
     * @param Worksheet $worksheet
     * @param Semester $semester
     * @param int $row
     * @return int                   The next line to read
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function updateGroups(Worksheet $worksheet, Semester $semester, int $row): int
    {
        $newGroups = $this->readGroups($worksheet, $row);
        $formerGroups = $semester->getGroups();

        // Remove former groups
        foreach ($formerGroups as $formerGroup) {
            // If group does not exist anymore
            if ($newGroups->filter($this->filterSameId($formerGroup))->isEmpty()) {
                $semester->removeGroup($formerGroup);
            }
        }

        // Add new groups and update modified ones
        foreach ($newGroups as $newGroup) {
            /** @var Group $formerGroup */
            $formerGroup = $formerGroups->filter($this->filterSameId($newGroup))->current();

            // If group did exist
            if ($formerGroup !== false) {
                // Update group number
                $formerGroup->setNumber($newGroup->getNumber());

                $newStudents = $newGroup->getStudents();
                $formerStudents = $formerGroup->getStudents();

                // Remove former students
                foreach ($formerStudents as $formerStudent) {
                    // If student does not exist anymore
                    if ($newStudents->filter($this->filterSameUsername($formerStudent))->isEmpty()) {
                        $formerGroup->removeStudent($formerStudent);
                    }
                }

                // Add new students
                foreach ($newStudents as $newStudent) {
                    if ($formerStudents->filter($this->filterSameUsername($newStudent))->isEmpty()) {
                        $formerGroup->addStudent($newStudent);
                    }
                }
            } else {
                // Add it to the collection
                $semester->addGroup($newGroup);
            }
        }

        return $row;
    }

    /**
     * Read the groups in a semester file
     *
     * @param Worksheet $worksheet
     * @param int $row
     * @return Collection           The read groups
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function readGroups(Worksheet $worksheet, int &$row): Collection
    {
        $groups = new ArrayCollection();

        while ($worksheet->getCell('A'.$row)->getValue() !== null) {
            $group = new Group();
            $group->setNumber($groups->count() + 1);

            // Id
            $id = $worksheet->getCell('B'.$row)->getValue();
            if (is_numeric($id)) {
                $group->setId((int) $id);
            }

            $row += 1;

            // Students
            while (($username = $worksheet->getCell('B'.$row)->getValue()) !== null) {
                $group->addStudent((new Student())
                    ->setUsername($username));

                $row += 1;
            }

            $row += 1;

            $groups->add($group);
        }

        return $groups;
    }

    private function filterSameId($element)
    {
        $id = $element->getId();
        return function($tested) use ($id) {
            return $tested->getId() === $id;
        };
    }

    private function filterSameUsername($element)
    {
        $username = $element->getUsername();
        return function($tested) use ($username) {
            return $tested->getUsername() === $username;
        };
    }
}

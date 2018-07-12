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
        return 2; // Skip header
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
        $semester->setId(
            (int) $worksheet->getCell('B'.$row)->getValue());

        $row += 1;

        $course = $semester->getCourse() ?? new Course();
        $course->setSemester(
            (int) $worksheet->getCell('B'.$row)->getValue());

        $course->setImplementationDate(
            Date::excelToDateTimeObject($worksheet->getCell('B'.$row)->getValue()));

        $row += 1;

        $semester->setStartDate(
            Date::excelToDateTimeObject($worksheet->getCell('B'.$row)->getValue()));

        $row += 1;

        $semester->setEndDate(
            Date::excelToDateTimeObject($worksheet->getCell('B'.$row)->getValue()));

        $row += 1;

        $row = $this->updateGroups($worksheet, $semester->getGroups(), $row);

        return $row;
    }

    /**
     * Update the collection of group.
     *
     * @param Worksheet $worksheet
     * @param Collection $formerGroups
     * @param int $row
     * @return int                      The next line to read
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function updateGroups(Worksheet $worksheet, Collection $formerGroups, int $row): int
    {
        $newGroups = $this->readGroups($worksheet, $row);

        // Add new groups and update modified ones
        foreach ($newGroups as $newGroup) {
            /** @var Group $formerGroup */
            $formerGroup = $formerGroups->filter($this->filterSameId($newGroup))->current();

            // If group did exist
            if ($formerGroup !== FALSE) {
                // Compute modifications
                $formerGroup->setNumber($newGroup->getNumber());

                $formerStudents = $formerGroup->getStudents();
                $newStudents = $newGroup->getStudents();

                // Add new students
                $newStudents->forAll(function($key, $newStudent) use ($formerStudents) {
                    // If student did not exist
                    if ($formerStudents->filter($this->filterSameUsername($newStudent))->count() === 0) {
                        $formerStudents->add($newStudent);
                    }
                });

                // Remove former students
                foreach ($formerStudents as $key => $formerStudent) {
                    // If student does not exist anymore
                    if ($newStudents->filter($this->filterSameUsername($formerStudent))->count() !== 0) {
                        $formerStudents->remove($key);
                    }
                }
            } else {
                // Add it to the collection
                $formerGroups->add($newGroup);
            }
        }

        // Remove former groups
        foreach ($formerGroups as $key => $formerGroup) {
            if ($newGroups->filter($this->filterSameId($formerGroup))->count() === 0) {
                $formerGroups->remove($key);
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
                $student = new Student();
                $student->setUsername($username);

                if ($firstname = $worksheet->getCell('C'.$row)->getValue()) {
                    $student->setFirstname($firstname);
                }

                if ($surname = $worksheet->getCell('C'.$row)->getValue()) {
                    $student->setSurname($surname);
                }

                $group->addStudent($student);

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

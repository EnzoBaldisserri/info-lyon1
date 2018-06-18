<?php

namespace App\Repository\Administration;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\Administration\Group;
use App\Entity\Administration\Semester;

/**
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Group::class);
    }

    public function findInSemestersWithAbsences(Array $semesters)
    {
        $period = reset($semesters)->getPeriod();

        $qb = $this->createQueryBuilder('g');

        // Filter groups
        $qb
            ->andWhere($qb->expr()->in('g.semester', ':semesters'))
              ->setParameter('semesters', $semesters)
        ;

        // Order the groups by type of semester and number
        // G6S1 < G2S3 < G4S3 < G1S4
        $qb
            ->join('g.semester', 'sem')
            ->join('sem.course', 'c')
            ->addOrderBy('c.semester', 'ASC')
            ->addOrderBy('g.number', 'ASC')
        ;

        // Join students
        // Order them by surname and name
        $qb
            ->innerJoin('g.students', 's')
            ->addSelect('s')
            ->addOrderBy('s.surname', 'ASC')
            ->addOrderBy('s.firstname', 'ASC')
        ;

        // Add absences
        // That are in the semester
        // Order them by time
        $qb
            ->leftJoin('s.absences', 'a')
            ->addSelect('a')
            ->andWhere($qb->expr()->orX(
                $qb->expr()->isNull('a.startTime'),
                $qb->expr()->between('a.startTime', ':start', ':end')
            ))
              ->setParameter('start', $period->getStartDate())
              ->setParameter('end', $period->getEndDate())
            ->addOrderBy('a.startTime', 'ASC')
        ;

        $groups = $qb->getQuery()
            ->getResult();

        // Compute days of the semester
        // With a hash used as a key in React
        $days = $this->getDaysWithHash($period);

        // Group absences in days
        foreach ($groups as $group) {
            $students = $group->getStudents();

            foreach ($students as $student) {
                $absences = $student->getAbsences();
                $organizedAbsences = $this->organizeAbsences($absences, $days);
                $student->setAbsences($organizedAbsences);
            }
        }

        return $groups;
    }

    private function getDaysWithHash(Period $period): Array
    {
        $days = [];

        $currDate = $period->getStartDate();
        $endDate = $period->getEndDate();

        // While semDate is before end
        while ($currDate <= $endDate) {
            $date = $currDate->format('Y-m-d');

            $days[$date] = [
                'hash' => $date,
                'absences' => [],
            ];
            $semDate->modify('+1 day');
        }

        return $days;
    }

    private function organizeAbsences(Iterable $absences, Array $days): Collection
    {
        foreach ($absences as $absence) {
            $date = $absence->getStartTime()->format('Y-m-d');

            $days[$date]['absences'][] = $absence;
        }

        return new ArrayCollection(array_values($days));
    }
}

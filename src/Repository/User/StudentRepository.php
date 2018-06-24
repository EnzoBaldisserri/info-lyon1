<?php

namespace App\Repository\User;

use App\Entity\User\Student;
use App\Entity\Administration\Group;
use App\Entity\Administration\Semester;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Student::class);
    }

    /**
     * @param Group $group
     * @return Student[]
     */
    public function findInGroup(Group $group): array
    {
        $qb = $this->createQueryBuilder('s');

        $qb
            ->join('s.classes', 'c')
            ->andWhere($qb->expr()->eq('c', ':group'))
              ->setParameter('group', $group)
        ;

        $qb
            ->addOrderBy('s.surname', 'ASC')
            ->addOrderBy('s.firstname', 'ASC')
        ;

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @param Semester $semester
     * @return Student[]
     */
    public function findAvailableForSemester(Semester $semester): array
    {
        // Filter students that already have a group for this period
        $qb = $this->createQueryBuilder('stu');

        $qb
            ->join('stu.classes', 'grp')
            ->join('grp.semester', 'sem')
            ->andWhere($qb->expr()->eq('sem.startDate', ':startDate'))
        ;

        $unavailables = $qb->getQuery();

        $qb = $this->createQueryBuilder('s');

        $qb
            ->andWhere($qb->expr()->notIn('s', $unavailables->getDQL()))
              ->setParameter('startDate', $semester->getStartDate());

        // TODO find students potentially interested for semester
        /*
         * - Students that succeeded semester n-1
         * - Students that failed semester n
         * - Students that failed a semester at some point, but got to the next anyway
         * ...
         */

        $qb
            ->addOrderBy('s.surname', 'ASC')
            ->addOrderBy('s.firstname', 'ASC')
        ;

        return $qb->getQuery()
            ->getResult();
    }
}

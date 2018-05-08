<?php

namespace App\Repository\Administration;

use App\Entity\Administration\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

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

    public function findInSemesterWithAbsences($semester)
    {
        $qb = $this->createQueryBuilder('g');

        // Order the groups by type of semester + by number
        // G6S2 < G2S3 < G3S3 < G1S4
        $qb
            // ->join('g.semester', 'sem')
            // ->join('sem.course', 'c')
            // ->addOrderBy('c.semester', 'ASC')
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
            ->andWhere($qb->expr()->between('a.startTime', ':start', ':end'))
              ->setParameter('start', $oneSemester->getStartDate())
              ->setParameter('end', $oneSemester->getEndDate())
            ->orderBy('a.startTime', 'ASC')
        ;

        return $qb->getQuery()
            ->getResult();
    }
}

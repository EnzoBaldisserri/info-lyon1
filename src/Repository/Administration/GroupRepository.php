<?php

namespace App\Repository\Administration;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\Administration\Group;
use App\Entity\Administration\Semester;
use App\Entity\Period;

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

    /**
     * @param Semester[] $semesters
     * @return Group[]
     */
    public function findInSemestersWithAbsences(array $semesters)
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
              ->setParameter('start', $period->getStart())
              ->setParameter('end', $period->getEnd())
            ->addOrderBy('a.startTime', 'ASC')
        ;

        return $qb->getQuery()
            ->getResult();
    }
}

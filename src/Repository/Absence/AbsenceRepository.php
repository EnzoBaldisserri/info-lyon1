<?php

namespace App\Repository\Absence;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\Absence\Absence;
use App\Entity\Period;
use App\Entity\User\Student;

/**
 * @method Absence|null find($id, $lockMode = null, $lockVersion = null)
 * @method Absence|null findOneBy(array $criteria, array $orderBy = null)
 * @method Absence[]    findAll()
 * @method Absence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbsenceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Absence::class);
    }

    /**
     * @param Period $period
     * @param Student $student
     * @return Absence[]
     */
    public function getInPeriodForStudent(Period $period, Student $student): array
    {
        $qb = $this->createQueryBuilder('a');

        $qb
            ->andWhere($qb->expr()->eq('a.student', ':student'))
              ->setParameter('student', $student);
        ;

        $qb
            ->andWhere($qb->expr()->between('a.startTime', ':start', ':end'))
              ->setParameter('start', $period->getStart())
              ->setParameter('end', $period->getEnd())
            ->addOrderBy('a.startTime', 'ASC')
        ;

        return $qb->getQuery()
            ->getResult();
    }
}

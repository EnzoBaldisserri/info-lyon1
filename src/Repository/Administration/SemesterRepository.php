<?php

namespace App\Repository\Administration;

use App\Entity\Administration\Semester;
use App\Entity\Period;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Semester|null find($id, $lockMode = null, $lockVersion = null)
 * @method Semester|null findOneBy(array $criteria, array $orderBy = null)
 * @method Semester[]    findAll()
 * @method Semester[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SemesterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Semester::class);
    }

    public function findCurrent(): Array
    {
        $now = new \DateTime();
        return $this->findAtDate($now);
    }

    public function findAtDate(\DateTime $datetime): Array
    {
        $qb = $this->createQueryBuilder('s');

        $qb
            ->andWhere($qb->expr()->between(':datetime', 's.startDate', 's.endDate'))
              ->setParameter('datetime', $datetime)
        ;

        return $qb->getQuery()
            ->getResult();
    }

    public function findFuture()
    {
        $now = new DateTime();
        return $this->findAfter($now, 'startDate');
    }

    public function findAfter(\DateTime $datetime, string $boundary = 'startDate'): Array
    {
        if (!in_array($boundary, ['startDate', 'endDate'])) {
            throw new Exception('Boundary is neither \'startDate\' nor \'endDate\'');
        }

        $qb = $this->createQueryBuilder('s');

        $qb
            ->andWhere($qb->expr()->gt("s.$boundary", ':datetime'))
                ->setParameter('datetime', $datetime)
        ;

        return $qb->getQuery()
            ->getResult();
    }

    public function findCurrentPeriod(): Period
    {
        $now = new \DateTime();
        return $this->findPeriodAt($now);
    }

    public function findPeriodAt(\DateTime $datetime): Period
    {
        $qb = $this->createQueryBuilder('s');

        $qb
            ->andWhere($qb->expr()->between(':datetime', 's.startDate', 's.endDate'))
              ->setParameter('datetime', $datetime)
            ->setMaxResults(1)
        ;

        $semester = $qb->getQuery()
            ->getOneOrNullResult();

        if (null === $semester) {
            return null;
        }

        return $semester->getPeriod();
    }
}

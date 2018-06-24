<?php

namespace App\Repository\Administration;

use DateTime;
use RuntimeException;
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

    /**
     * @return Semester[]
     */
    public function findCurrent(): array
    {
        $now = new DateTime();
        return $this->findAtDate($now);
    }

    /**
     * @param DateTime $datetime
     * @return Semester[]
     */
    public function findAtDate(DateTime $datetime): array
    {
        $qb = $this->createQueryBuilder('s');

        $qb
            ->andWhere($qb->expr()->between(':datetime', 's.startDate', 's.endDate'))
              ->setParameter('datetime', $datetime)
        ;

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @return Semester[]
     */
    public function findFuture()
    {
        $now = new DateTime();
        return $this->findAfter($now, 'startDate');
    }

    /**
     * @param DateTime $datetime
     * @param string $boundary Either the 'startDate' or the 'endDate' of the semester
     * @return Semester[]
     */
    public function findAfter(DateTime $datetime, string $boundary = 'startDate'): array
    {
        if (!in_array($boundary, ['startDate', 'endDate'])) {
            throw new RuntimeException('Boundary is neither \'startDate\' nor \'endDate\'');
        }

        $qb = $this->createQueryBuilder('s');

        $qb
            ->andWhere($qb->expr()->gt("s.$boundary", ':datetime'))
                ->setParameter('datetime', $datetime)
        ;

        $qb->addOrderBy("s.$boundary", 'DESC');

        $qb
            ->join('s.course', 'c')
            ->addOrderBy('c.semester', 'ASC')
        ;

        return $qb->getQuery()
            ->getResult();
    }

    public function findCurrentPeriod(): Period
    {
        $now = new DateTime();
        return $this->findPeriodAt($now);
    }

    public function findPeriodAt(DateTime $datetime): Period
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

    public function findNextPeriod()
    {
        $current = clone $this->findCurrentPeriod();

        return new Period(
            $current->getEnd()->modify('+1 day'),
            $current->getStart()
                ->modify('-1 day')
                ->modify('+1 year')
        );
    }
}

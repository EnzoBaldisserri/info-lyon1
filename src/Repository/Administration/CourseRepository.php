<?php

namespace App\Repository\Administration;

use DateTime;
use App\Entity\Administration\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Course|null find($id, $lockMode = null, $lockVersion = null)
 * @method Course|null findOneBy(array $criteria, array $orderBy = null)
 * @method Course[]    findAll()
 * @method Course[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Course::class);
    }

    /**
     * @return Course[]
     */
    public function findEditable(): array
    {
        $now = new DateTime();
        return $this->findEditableAt($now);
    }

    /**
     * @param DateTime $datetime
     * @return Course[]
     */
    public function findEditableAt(DateTime $datetime): array
    {
        $qb = $this->createQueryBuilder('c');

        $qb
            ->andWhere($qb->expr()->gte('c.implementationDate', ':datetime'))
              ->setParameter('datetime', $datetime)
        ;

        $qb
            ->addOrderBy('c.implementationDate', 'DESC')
            ->addOrderBy('c.semester', 'ASC')
        ;

        return $qb->getQuery()
            ->getResult();
    }

    /**
     * @param int $semester
     * @return Course|null
     */
    public function findLastOneBySemester(int $semester)
    {
        $qb = $this->createQueryBuilder('c');

        $qb
            ->andWhere($qb->expr()->eq('c.semester', ':semester'))
              ->setParameter('semester', $semester)
        ;

        $qb
            ->addOrderBy('c.id', 'DESC')
            ->setMaxResults(1)
        ;

        return $qb->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param int $semester
     * @param int $year
     * @return Course|null
     */
    public function findOneBySemesterAndYear(int $semester, int $year): ?Course
    {
        $qb = $this->createQueryBuilder('c');

        // Semester
        $qb
            ->andWhere($qb->expr()->eq('c.semester', ':semester'))
              ->setParameter('semester', $semester)
        ;

        // Year
        $qb
            ->andWhere($qb->expr()->between('c.implementationDate', ':yearStart', ':yearEnd'))
              ->setParameter('yearStart', new DateTime($year . '-01-01'))
              ->setParameter('yearEnd', new DateTime($year . '-12-31'))
        ;

         return $qb->getQuery()
            ->getOneOrNullResult();
    }
}

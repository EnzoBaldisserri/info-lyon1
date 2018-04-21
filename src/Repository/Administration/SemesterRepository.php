<?php

namespace App\Repository\Administration;

use App\Entity\Administration\Semester;
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

    public function findOneOfCurrent(): ?Semester
    {
        $now = new \DateTime();
        return $this->findOneAtDate($now);
    }

    public function findOneAtDate(\DateTime $datetime): ?Semester
    {
        $qb = $this->createQueryBuilder('s');

        $qb
            ->andWhere($qb->expr()->between(':datetime', 's.startDate', 's.endDate'))
            ->setParameter('datetime', $datetime)
        ;

        $qb->setMaxResults(1);

        return $qb->getQuery()
            ->getOneOrNullResult();
    }

}

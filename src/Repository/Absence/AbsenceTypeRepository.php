<?php

namespace App\Repository\Absence;

use App\Entity\Absence\AbsenceType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AbsenceType|null find($id, $lockMode = null, $lockVersion = null)
 * @method AbsenceType|null findOneBy(array $criteria, array $orderBy = null)
 * @method AbsenceType[]    findAll()
 * @method AbsenceType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbsenceTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AbsenceType::class);
    }

    public function deleteAll(): integer
    {
        $qb = $this->createQueryBuilder()
            ->delete()
            ->andWhere(true)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return AbsenceType[] Returns an array of AbsenceType objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AbsenceType
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

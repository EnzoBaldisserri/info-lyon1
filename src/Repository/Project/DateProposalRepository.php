<?php

namespace App\Repository\Project;

use App\Entity\Project\DateProposal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DateProposal|null find($id, $lockMode = null, $lockVersion = null)
 * @method DateProposal|null findOneBy(array $criteria, array $orderBy = null)
 * @method DateProposal[]    findAll()
 * @method DateProposal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DateProposalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DateProposal::class);
    }

//    /**
//     * @return DateProposal[] Returns an array of DateProposal objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DateProposal
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

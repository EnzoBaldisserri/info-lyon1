<?php

namespace App\Repository\Administration;

use App\Entity\Administration\TeachingUnit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TeachingUnit|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachingUnit|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachingUnit[]    findAll()
 * @method TeachingUnit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachingUnitRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TeachingUnit::class);
    }

}

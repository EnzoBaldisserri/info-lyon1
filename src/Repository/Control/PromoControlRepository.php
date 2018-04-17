<?php

namespace App\Repository\Control;

use App\Entity\Control\PromoControl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PromoControl|null find($id, $lockMode = null, $lockVersion = null)
 * @method PromoControl|null findOneBy(array $criteria, array $orderBy = null)
 * @method PromoControl[]    findAll()
 * @method PromoControl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromoControlRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PromoControl::class);
    }
}

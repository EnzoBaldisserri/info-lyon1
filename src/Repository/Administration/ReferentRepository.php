<?php

namespace App\Repository\Administration;

use App\Entity\Administration\Referent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Referent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Referent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Referent[]    findAll()
 * @method Referent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Referent::class);
    }

}

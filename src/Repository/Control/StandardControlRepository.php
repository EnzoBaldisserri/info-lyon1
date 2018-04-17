<?php

namespace App\Repository\Control;

use App\Entity\Control\StandardControl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method StandardControl|null find($id, $lockMode = null, $lockVersion = null)
 * @method StandardControl|null findOneBy(array $criteria, array $orderBy = null)
 * @method StandardControl[]    findAll()
 * @method StandardControl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StandardControlRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StandardControl::class);
    }
}

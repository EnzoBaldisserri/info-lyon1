<?php

namespace App\Repository\User;

use App\Entity\User\Secretariat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Secretariat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Secretariat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Secretariat[]    findAll()
 * @method Secretariat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecretariatRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Secretariat::class);
    }

}

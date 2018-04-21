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

}

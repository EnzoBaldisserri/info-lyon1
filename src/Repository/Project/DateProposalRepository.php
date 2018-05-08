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

}

<?php

namespace App\Repository\Project;

use App\Entity\Project\DateAccept;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DateAccept|null find($id, $lockMode = null, $lockVersion = null)
 * @method DateAccept|null findOneBy(array $criteria, array $orderBy = null)
 * @method DateAccept[]    findAll()
 * @method DateAccept[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DateAcceptRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DateAccept::class);
    }

}

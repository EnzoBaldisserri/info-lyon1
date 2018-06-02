<?php

namespace App\Repository\User;

use App\Entity\User\Notification;
use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    /**
     * Remove user's notifications in the database
     *
     * @param User $user The user
     * @return int       The number of notifications deleted
     */
    public function clearForUser(User $user)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb
            ->delete(Notification::class, 'n')
            ->where($qb->expr()->eq('n.user', ':user'))
              ->setParameter('user', $user);

        return $qb->getQuery()
            ->execute();
    }
}

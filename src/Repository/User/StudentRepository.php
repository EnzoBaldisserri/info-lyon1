<?php

namespace App\Repository\User;

use App\Entity\User\Student;
use App\Entity\Administration\Group;
use App\Entity\Administration\Semester;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Student::class);
    }

    public function findInGroup(Group $group): Array
    {
        $qb = $this->createQueryBuilder('s');

        $qb
            ->join('s.classes', 'c')
            ->andWhere($qb->expr()->eq('c', ':group'))
              ->setParameter('group', $group)
        ;

        $qb
            ->addOrderBy('s.surname', 'ASC')
            ->addOrderBy('s.firstname', 'ASC')
        ;

        return $qb->getQuery()
            ->getResult();
    }

    public function findAvailableForSemester(Semester $semester): Array
    {
        // TODO find students available for semester
        $qb = $this->createQueryBuilder('s');

        $qb
            ->addOrderBy('s.surname', 'ASC')
            ->addOrderBy('s.firstname', 'ASC')
        ;

        return $qb->getQuery()
            ->getResult();
    }
}

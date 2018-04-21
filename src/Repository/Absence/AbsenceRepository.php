<?php

namespace App\Repository\Absence;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\Absence\Absence;
use App\Entity\Administration\Semester;
use App\Entity\User\Student;

/**
 * @method Absence|null find($id, $lockMode = null, $lockVersion = null)
 * @method Absence|null findOneBy(array $criteria, array $orderBy = null)
 * @method Absence[]    findAll()
 * @method Absence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbsenceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Absence::class);
    }

    public function getInSemesterForStudent(Semester $semester, Student $student)
    {
        $qb = $this->createQueryBuilder('a');

        $qb
            ->andWhere($qb->expr()->eq('a.student', ':student'))
            ->setParameter('student', $student);
        ;

        $qb
            ->andWhere($qb->expr()->between('a.startTime', ':start', ':end'))
            ->setParameter('start', $semester->getStartDate())
            ->setParameter('end', $semester->getEndDate())
        ;

        return $qb->getQuery()
            ->getResult();
    }
}

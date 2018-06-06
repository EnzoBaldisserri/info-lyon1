<?php

namespace App\Repository\Absence;

use App\Entity\Absence\AbsenceType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @method AbsenceType|null find($id, $lockMode = null, $lockVersion = null)
 * @method AbsenceType|null findOneBy(array $criteria, array $orderBy = null)
 * @method AbsenceType[]    findAll()
 * @method AbsenceType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbsenceTypeRepository extends ServiceEntityRepository
{
    private $translator;

    public function __construct(RegistryInterface $registry, TranslatorInterface $translator)
    {
        parent::__construct($registry, AbsenceType::class);

        $this->translator = $translator;
    }

    public function findAllWithNames(): Array
    {
        $absenceTypesRaw = $this->findAll();

        $absenceTypes = [];
        foreach ($absenceTypesRaw as $type) {
            $name = $type->getName();
            $absenceTypes[$name] = $this->translator->trans("absence.type.$name");
        }

        return $absenceTypes;
    }
}

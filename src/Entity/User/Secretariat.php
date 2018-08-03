<?php

namespace App\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\User\SecretariatRepository")
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Secretariat extends User
{

}

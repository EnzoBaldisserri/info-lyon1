<?php

namespace App\Entity\User;

use App\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\User\SecretariatRepository")
 */
class Secretariat extends User
{

}

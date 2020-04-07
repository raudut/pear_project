<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LenderRepository")
 */
class Lender
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $idlender;
    private $iduser;

    public function getIdlender(): ?int
    {
        return $this->idlender;
    }
    public function getIduser(): ?int
    {
        return $this->iduser;
    }

}

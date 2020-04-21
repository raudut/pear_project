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
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="lender", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $iduser;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIduser(): ?User
    {
        return $this->iduser;
    }

    public function setIduser(User $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }

    
}

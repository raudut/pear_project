<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $caution;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $emplacement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numSerie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $kit;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Borrowing", mappedBy="id_product", orphanRemoval=true)
     */
    private $borrowings;

    /**
     * @ORM\Column(type="json")
     */
    private $statut = [];

    

    public function __construct()
    {
        $this->borrowings = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCaution(): ?string
    {
        return $this->caution;
    }

    public function setCaution(string $caution): self
    {
        $this->caution = $caution;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getEmplacement(): ?string
    {
        return $this->emplacement;
    }

    public function setEmplacement(string $emplacement): self
    {
        $this->emplacement = $emplacement;

        return $this;
    }

    public function getNumSerie(): ?string
    {
        return $this->numSerie;
    }

    public function setNumSerie(string $numSerie): self
    {
        $this->numSerie = $numSerie;

        return $this;
    }

    public function getKit(): ?string
    {
        return $this->kit;
    }

    public function setKit(string $kit): self
    {
        $this->kit = $kit;

        return $this;
    }


    public function __toString()
        {
            return $this->nom;
        }

    /**
     * @return Collection|Borrowing[]
     */
    public function getBorrowings(): Collection
    {
        return $this->borrowings;
    }

    public function addBorrowing(Borrowing $borrowing): self
    {
        if (!$this->borrowings->contains($borrowing)) {
            $this->borrowings[] = $borrowing;
            $borrowing->setIdProduct($this);
        }

        return $this;
    }

    public function removeBorrowing(Borrowing $borrowing): self
    {
        if ($this->borrowings->contains($borrowing)) {
            $this->borrowings->removeElement($borrowing);
            // set the owning side to null (unless already changed)
            if ($borrowing->getIdProduct() === $this) {
                $borrowing->setIdProduct(null);
            }
        }

        return $this;
    }

    

    public function getStatutNames(){
        $statut = $this->getStatut('security.statut_hierarchy'); 
        return array(
            "Loue" => "STATUT_LOUE",
            "Indisponible" => "STATUT_INDISPONIBLE",
            "Disponible" => "STATUT_DISPONIBLE",        
        );
    }

    public function getStatut(): array
    {
        $statut = $this->statut;
        // guarantee every user at least has ROLE_USER
        $statut[] = 'STATUT_INDISPONIBLE';

        return array_unique($statut);
    }

    public function setStatut(array $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    

 
}


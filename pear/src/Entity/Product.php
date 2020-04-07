<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
use Doctrine\Common\Collections\ArrayCollection;

class Product
{
  private $id;
  private $nom;
  private $prix;
  private $etat;
  private $caution;
  private $emplacement;
  private $num_serie;
  private $kit;

  public function getId()
  {
    return $this->id;
  }
   public function getNom()
  {
    return $this->nom;
  }
   public function getPrix()
  {
    return $this->prix;
  }
   public function getEtat()
  {
    return $this->etat;
  }
 public function getCaution()
  {
    return $this->caution;
  }
   public function getEmplacement()
  {
    return $this->emplacement;
  }
  public function getNumSerie()
  {
    return $this->num_serie;
  }
  public function getKit()
  {
    return $this->kit;
  }
  
  
  
}

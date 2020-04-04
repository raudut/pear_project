<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
use Doctrine\Common\Collections\ArrayCollection;

class User
{
  private $id;
  private $nom;
  private $prenom;
  private $email;
  private $password;
  private $status;

  public function getId()
  {
    return $this->id;
  }
   public function getNom()
  {
    return $this->nom;
  }
   public function getPrenom()
  {
    return $this->prenom;
  }
   public function getEmail()
  {
    return $this->email;
  }
 public function getPassword()
  {
    return $this->password;
  }
   public function getStatus()
  {
    return $this->status;
  }
  public function __construct()
  {
    $this->status   = new ArrayCollection();
  }
  
  // … Les getters et setters
}

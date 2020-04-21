<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

// HERE 
    /**
     * @ORM\Column(type="date")
     */
    private $naissance;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Borrowing", mappedBy="id_user", orphanRemoval=true)
     */
    private $borrowings;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Lender", mappedBy="iduser", cascade={"persist", "remove"})
     */
    private $lender;

    public function __construct()
    {
        $this->borrowings = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_LENDER';

        return array_unique($roles);
    }
    public function getRolesNames(){
        $roles = $this->getRoles('security.role_hierarchy'); 
        return array(
            "Administrateur" => "ROLE_ADMIN",
            "Loueur" => "ROLE_BORROWER",
            "Preteur" => "ROLE_LENDER",        
        );
    }
    public function setRoles(array $roles): self
    {
        
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
// HERE
public function getNom(): ?string
{
    return $this->nom;
}

public function setNom(string $nom): self
{
    $this->nom = $nom;

    return $this;
}

public function getPrenom(): ?string
{
    return $this->prenom;
}

public function setPrenom(string $prenom): self
{
    $this->prenom = $prenom;

    return $this;
}


public function getNaissance(): ?\DateTimeInterface
{
    return $this->naissance;
}

public function setNaissance(\DateTimeInterface $naissance): self
{
    $this->naissance = $naissance;

    return $this;
}

public function __toString()
        {
            return $this->prenom . ' ' . $this->nom;
        }



    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $borrowing->setIdUser($this);
        }

        return $this;
    }

    public function removeBorrowing(Borrowing $borrowing): self
    {
        if ($this->borrowings->contains($borrowing)) {
            $this->borrowings->removeElement($borrowing);
            // set the owning side to null (unless already changed)
            if ($borrowing->getIdUser() === $this) {
                $borrowing->setIdUser(null);
            }
        }

        return $this;
    }

    public function getLender(): ?Lender
    {
        return $this->lender;
    }

    public function setLender(Lender $lender): self
    {
        $this->lender = $lender;

        // set the owning side of the relation if necessary
        if ($lender->getIduser() !== $this) {
            $lender->setIduser($this);
        }

        return $this;
    }
}

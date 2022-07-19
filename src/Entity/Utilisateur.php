<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 * @UniqueEntity(
 *    fields={"email"}, message="Un compte existe déjà à cette adresse mail")
 */
class Utilisateur implements UserInterface
{

    // Pour gérer l'authentification symfony a besoin que l'entité Utilisateur implémente 2 interfaces.
    // La première, la UserInterface nécessitte que notre entité implémentes plusieurs méthode:
    // getRoles()
    // getUsername()
    // getPassword()
    // getUserIdentifier()  qui permet d'indiquer à l'application sur quelle propriété l'utilisateur est authentifier (généralement l'email)
    // eraseCredentials()  qui implémentée avec un retour a pour objectif de scanner tout les mots de passes en BDD et de supprimer tout ceux entrés en BRUT (non Hachés)
    // getSalt() qui une fois implémenté permet de retourner la valeur en TEXTE BRUT des mots de passes hashé en BDD
    
    // ensuite la seconde interface, PasswordAuthenticatedInterface, est nécessaire pour la prise en compte de nos clés cryptage (pour hasher les mots de passes) et la gestion de la sécurité de symfony.




    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="veuillez saisir ce champs")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Email Invalide")
     * @Assert\NotBlank(message="veuillez saisir ce champs")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="veuillez saisir ce champs")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="les mots de passes ne sont pas identiques")
     */
    public $confirmPassword;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = ["ROLE_USER"];

    /**
     * @ORM\OneToMany(targetEntity=Commande::class, mappedBy="utilisateur")
     */
    private $commandes;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {

        $this->username = $username;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }


    public function getUserIdentifier()
    {
        return $this->email;
    }

    public function getSalt()
    {
        
    }

    public function eraseCredentials()
    {
        
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setUtilisateur($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getUtilisateur() === $this) {
                $commande->setUtilisateur(null);
            }
        }

        return $this;
    }




}

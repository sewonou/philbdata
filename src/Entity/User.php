<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *      fields={"login"},
 *      message = "Un utilisateur utilise déja ce login",
 * )
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Veuillez saisir un prénom valide")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Veuillez saisir un nom valide")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Le login n'est pas valide")
     * @Assert\Length(
     *     min = 5,
     *     max = 18,
     *     minMessage = "Votre login ne peut pas contenir moins de 5 caractères",
     *     maxMessage = "Votre login ne peut pa contenir plus de 18 caractères")
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Le mot de passe n'est pas valide")
     * @Assert\Length(
     *     min = 8,
     *     minMessage = "Votre mot de passe ne peut pas contenir moins de 8 caractères")
     */
    private $password;

    /**
     * @Assert\EqualTo(
     *     propertyPath="password",
     *     message="Vous n'avez pas correctement confirmé votre mot de passe")
     */
    public $passwordConfirm ;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Vous n'avez pas saisit la fonction de l'utilisateur")
     */
    private $office;

    /**
     * @Assert\File(
     *     maxSize="1024k",
     *     maxSizeMessage="Le fichier ne doit pas excéder 1024Ko"
     * )
     * @Vich\UploadableField(mapping="user_image", fileNameProperty="pictureName")
     * @var File|null
     */
    private $pictureFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pictureName;



    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, inversedBy="users")
     * @Assert\NotBlank(message="Vous n'avez pas choisit le niveau d'accès de l'utilisateur")
     */
    private $userRoles;

    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
    }

    public function initialized()
    {
        $this->updateAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getOffice(): ?string
    {
        return $this->office;
    }

    public function setOffice(?string $office): self
    {
        $this->office = $office;

        return $this;
    }


    public function getPictureName(): ?string
    {
        return $this->pictureName;
    }

    public function setPictureName(?string $pictureName): self
    {
        $this->pictureName = $pictureName;
        return $this;
    }

    /**
     * @param File|UploadedFile|null $pictureFile
     * @throws Exception
     */
    public function setPictureFile(?File  $pictureFile = null): void
    {
        $this->pictureFile = $pictureFile;
        if (null !== $pictureFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updateAt = new \DateTimeImmutable();
        }

    }

    public function getPictureFile(): ?File
    {
        return $this->pictureFile;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
        }

        return $this;
    }

    public function getUsername()
    {
        return $this->getLogin();
    }

    public function getRoles()
    {
        $roles = $this->userRoles->map(function($role){
            return $role->getTitle();
        })->toArray();
        $roles[] = 'ROLE_USER';
        return $roles;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getFullName()
    {
        return $this->firstName .' '. $this->lastName;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->login,
            $this->password,
            $this->userRoles,
            $this->firstName,
            $this->lastName,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->login,
            $this->password,
            $this->userRoles,
            $this->firstName,
            $this->lastName,
            ) = unserialize($serialized);
    }

}

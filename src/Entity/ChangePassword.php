<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;


class ChangePassword
{


    /**
     * @SecurityAssert\UserPassword(
     *     message = "L'ancien mot de passe saisi n'est pas correct")
     */
    private $oldPassword;

    /**
     * @Assert\Length(
     *     min = 8,
     *     max = 50,
     *     minMessage = "Votre mot de passe ne peut pas contenir moins de 8 caractères",
     *     maxMessage = "Votre mot de passe ne peut pa contenir plus de 50 caractères")
     */
    private $newPassword;

    /**
     * @Assert\EqualTo(
     *     propertyPath="newPassword",
     *     message="Vous n'avez pas confirmé votre nouveau mot de passe"
     * )
     */
    private $confirmPassword;


    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}

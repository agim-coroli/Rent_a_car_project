<?php

namespace model\mapping;

use DateTime;
use model\AbstractMapping;
use Exception;

class UserMapping extends AbstractMapping
{
    protected ?int $id = null;
    protected ?string $full_name = null;
    protected ?string $pseudo = null;
    protected ?string $email = null;
    protected ?string $email_token = null;
    protected ?DateTime $email_token_expires = null;
    protected ?string $phone = null;
    protected ?string $password = null;
    protected ?string $password_confirm = null;
    protected ?string $password_token = null;
    protected ?DateTime $password_token_expires = null;
    protected ?DateTime $date_birth = null;
    protected ?string $gender = null;
    protected ?bool $role = null;
    protected ?DateTime $created_at = null;
    protected ?bool $is_verified = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(?string $full_name): self
    {
        $full_name = $full_name !== null ? trim($full_name) : null;


        if ($full_name === null || $full_name === '') {
            throw new Exception("Le nom ne peut pas être vide.");
        }

        $this->full_name = $full_name;
        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): self
    {
        $pseudo = $pseudo !== null ? trim($pseudo) : null;

        if ($pseudo === null || $pseudo === '') {
            throw new Exception("Le pseudo ne peut pas être vide.");
        }

        $this->pseudo = $pseudo;
        return $this;
    }


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $email = $email !== null ? trim(strtolower($email)) : null;

        if ($email === null || $email === '') {
            throw new Exception("L'email ne peut pas être vide.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Format d'email invalide.");
        }

        $this->email = $email;
        return $this;
    }

    public function getEmailToken(): ?string
    {
        return $this->email_token;
    }

    public function setEmailToken(?string $email_token): self
    {
        $this->email_token = $email_token;
        return $this;
    }

    public function getEmailTokenExpires(): ?DateTime
    {
        return $this->email_token_expires;
    }



    public function setEmailTokenExpires($email_token_expires): self
    {
        if ($email_token_expires instanceof \DateTime) {
            $this->email_token_expires = $email_token_expires;
        } elseif (is_string($email_token_expires) && $email_token_expires !== '') {
            $this->email_token_expires = new \DateTime($email_token_expires);
        } else {
            $this->email_token_expires = null;
        }
        return $this;
    }


    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $phone = $phone !== null ? trim($phone) : null;

        if ($phone === null || $phone === '') {
            throw new Exception("Le numéro de téléphone ne peut pas être vide.");
        }

        if (strlen($phone) > 20) {
            throw new Exception("Le numéro de téléphone ne doit pas dépasser 20 caractères.");
        }

        if (!preg_match('/^(?:\+|00)?[0-9]{6,20}$/', $phone)) {
            throw new Exception("Format de numéro de téléphone invalide.");
        }

        $this->phone = $phone;
        return $this;
    }


    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        if ($password === null || $password === '') {
            return $this;
        }

        $password = trim($password);

        if (strpos($password, '$2y$') === 0) {
            $this->password = $password;
            return $this;
        }

        if (strlen($password) < 8) {
            throw new Exception("Le mot de passe doit contenir au moins 8 caractères.");
        }

        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    public function getPasswordConfirm(): ?string
    {
        return $this->password_confirm;
    }

    public function setPasswordConfirm(?string $password_confirm): self
    {
        if ($password_confirm === null || $password_confirm === '') {
            return $this;
        }

        $password_confirm = trim($password_confirm);

        if (strpos($password_confirm, '$2y$') === 0) {
            $this->password_confirm = $password_confirm;
            return $this;
        }

        if (strlen($password_confirm) < 8) {
            throw new Exception("Le mot de passe doit contenir au moins 8 caractères.");
        }

        $this->password_confirm = password_hash($password_confirm, PASSWORD_DEFAULT);
        return $this;
    }

    public function getPasswordToken(): ?string
    {
        return $this->password_token;
    }

    public function setPasswordToken(?string $password_token): self
    {
        $this->password_token = $password_token;
        return $this;
    }

    public function getPasswordTokenExpires(): ?DateTime
    {
        return $this->password_token_expires;
    }

    public function setPasswordTokenExpires($password_token_expires): self
    {
        if ($password_token_expires instanceof \DateTime) {
            $this->password_token_expires = $password_token_expires;
        } elseif (is_string($password_token_expires) && $password_token_expires !== '') {
            $this->password_token_expires = new \DateTime($password_token_expires);
        } else {
            $this->password_token_expires = null;
        }
        return $this;
    }

    public function setPasswordHash(?string $passwordHash): self
    {
        $this->password = $passwordHash;
        return $this;
    }



    public function getDateBirth(): ?DateTime
    {
        return $this->date_birth;
    }

    public function setDateBirth($date_birth): self
    {
        if ($date_birth instanceof DateTime) {
            $this->date_birth = $date_birth;
        } elseif (is_string($date_birth) && $date_birth !== '') {
            $date = DateTime::createFromFormat('Y-m-d', $date_birth);
            if ($date === false) {
                throw new Exception("Format de date invalide. Format attendu : YYYY-MM-DD (ex: 1990-01-15).");
            }
            $this->date_birth = $date;
        } else {
            $this->date_birth = null;
        }
        return $this;
    }



    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $gender = $gender !== null ? trim($gender) : null;

        if ($gender === null || $gender === '') {
            throw new Exception("Le genre ne peut pas être vide.");
        }

        $allowed = ['Masculin', 'Feminin'];
        if (!in_array($gender, $allowed, true)) {
            throw new Exception("Le genre doit être 'Masculin' ou 'Feminin'.");
        }

        $this->gender = $gender;
        return $this;
    }


    public function getRole(): ?bool
    {
        return $this->role;
    }

    public function setRole(?bool $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at): self
    {
        if ($created_at instanceof DateTime) {
            $this->created_at = $created_at;
        } elseif (is_string($created_at) && $created_at !== '') {
            $this->created_at = new DateTime($created_at);
        } else {
            $this->created_at = null;
        }
        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->is_verified;
    }
    public function setIsVerified(?bool $is_verified): self
    {
        $this->is_verified = $is_verified;
        return $this;
    }
}

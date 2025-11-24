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
    protected ?string $phone = null;
    protected ?string $password = null;
    protected ?DateTime $date_birth = null;
    protected ?string $gender = null;
    protected ?bool $role = null;
    protected ?DateTime $created_at = null;


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

        // Vérification format international ou national
        // Autorise : +32490120095, 0490120095, 0032490120095, etc.
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
        $password = $password !== null ? trim($password) : null;

        if ($password === null || $password === '') {
            throw new Exception("Le mot de passe ne peut pas être vide.");
        }

        if (strlen($password) < 8) {
            throw new Exception("Le mot de passe doit contenir au moins 8 caractères.");
        }

        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }



    public function getDateBirth(): ?\DateTime
    {
        return $this->date_birth;
    }

    public function setDateBirth(?string $date_birth): self
    {
        if ($date_birth === null || $date_birth === '') {
            throw new \Exception("La date de naissance ne peut pas être vide.");
        }

        $date = \DateTime::createFromFormat('Y-m-d', $date_birth);

        if (!$date) {
            throw new \Exception("Format de date invalide. Utilisez AAAA-MM-JJ.");
        }

        $this->date_birth = $date;
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

    // pas besoin ---------------------------

    // public function setRole(?bool $role): self
    // {
    //     $this->role = $role;
    //     return $this;
    // }

    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    // pas besoin ---------------------------

    // public function setCreatedAt(?DateTime $created_at): self
    // {
    //     $this->created_at = $created_at;
    //     return $this;
    // }
}

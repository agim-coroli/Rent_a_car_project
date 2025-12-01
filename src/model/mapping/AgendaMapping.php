<?php

namespace model\mapping;

use DateTime;
use model\AbstractMapping;
use Exception;

class AgendaMapping extends AbstractMapping
{
    protected ?int $id = null;
    protected ?int $vehicule_id = null;
    protected ?DateTime $date_reservation = null;
    protected ?DateTime $horaire = null;
    protected ?bool $is_reserved = null;
    protected ?int $user_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getVehiculeId(): ?int
    {
        return $this->vehicule_id;
    }
    public function setVehiculeId(?int $vehicule_id): self
    {
        if ($vehicule_id === null || $vehicule_id <= 0) {
            throw new Exception("L'identifiant du véhicule doit être valide.");
        }
        $this->vehicule_id = $vehicule_id;
        return $this;
    }

    public function getDateReservation(): ?DateTime
    {
        return $this->date_reservation;
    }
    public function setDateReservation($date): self
    {
        if ($date instanceof DateTime) {
            $this->date_reservation = $date;
        } elseif (is_string($date) && trim($date) !== '') {
            $this->date_reservation = new DateTime($date);
        } else {
            throw new Exception("La date de réservation est invalide.");
        }
        return $this;
    }

    public function getHoraire(): ?string
    {
        return $this->horaire ? $this->horaire->format('H:i') : null;
    }
    
    public function setHoraire($horaire): self
    {
        if ($horaire instanceof DateTime) {
            $this->horaire = $horaire;
        } elseif (is_string($horaire) && preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $horaire)) {
            $this->horaire = new DateTime($horaire);
        } else {
            throw new Exception("L'horaire doit être au format HH:MM ou HH:MM:SS.");
        }
        return $this;
    }

    public function getIsReserved(): ?bool
    {
        return $this->is_reserved;
    }
    public function setIsReserved(?bool $reserved): self
    {
        $this->is_reserved = $reserved ?? false;
        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }
    public function setUserId(?int $user_id): self
    {
        if ($user_id !== null && $user_id <= 0) {
            throw new Exception("L'identifiant utilisateur doit être valide.");
        }
        $this->user_id = $user_id;
        return $this;
    }

    // --- Méthode utilitaire ---
    public function isAvailable(): bool
    {
        return $this->is_reserved === false;
    }
}

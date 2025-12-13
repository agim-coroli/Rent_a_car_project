<?php

namespace model\mapping;

use DateTime;
use model\AbstractMapping;
use Exception;

class ReservationsMapping extends AbstractMapping
{
    private int $id;
    private int $vehiculeId;
    private int $userId;
    private string $dateDebut;
    private string $dateFin;
    private string $status;
    private string $createdAt;


    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getVehiculeId(): int
    {
        return $this->vehiculeId;
    }

    public function setVehiculeId(int $vehiculeId): void
    {
        $this->vehiculeId = $vehiculeId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getDateDebut(): string
    {
        return $this->dateDebut;
    }

    public function setDateDebut(string $dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }

    public function getDateFin(): string
    {
        return $this->dateFin;
    }

    public function setDateFin(string $dateFin): void
    {
        $this->dateFin = $dateFin;
    }


    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}

<?php

namespace model\manager;

use PDO;
use Exception;
use model\mapping\ReservationsMapping;
use model\ManagerInterface;


class ReservationsManager implements ManagerInterface
{
    private PDO $pdo;


    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    public function vehiculeReserved($data): bool
    {
        $stmt = $this->pdo->prepare("
INSERT INTO reservations
(vehicule_id, user_id, date_debut, date_fin)
VALUES
(:vehicule_id, :user_id, :date_debut, :date_fin);

UPDATE catalogue
SET status = 'reservé'
WHERE id = :vehicule_id
        ");

        try {
            $stmt->execute([
                ':vehicule_id' => $data->getVehiculeId(),
                ':user_id' => $data->getUserId(),
                ':date_debut' => $data->getDateDebut(),
                ':date_fin'   => $data->getDateFin()
            ]);
            return true;
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la reservation : " . $e->getMessage(), 0, $e);
        }
    }
}

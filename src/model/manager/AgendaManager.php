<?php

namespace model\manager;

use PDO;
use model\mapping\AgendaMapping;
use model\ManagerInterface;
use model\Exception\ExceptionFr;

class AgendaManager implements ManagerInterface
{
    protected PDO $pdo;


    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllDatesAndHours(): array
    {
        try {
            $sql = "SELECT date_reservation, horaire FROM agenda ORDER BY horaire ASC";
            $stmt = $this->pdo->query($sql);

            $results = [];
            while ($row = $stmt->fetch()) {
                $results[] = new AgendaMapping($row);
            }
            return $results;
        } catch (\Throwable $e) {
            throw new ExceptionFr("Erreur lors de la récupération des créneaux : " . $e->getMessage(), 0, $e);
        }
    }
}

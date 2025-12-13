<?php

namespace model\manager;

use PDO;
use Exception;
use model\mapping\CatalogueMapping;
use model\ManagerInterface;


class CatalogueManager implements ManagerInterface
{
    private PDO $pdo;


    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        try {
            $sql = "SELECT * FROM catalogue ORDER BY id ASC";
            $stmt = $this->pdo->query($sql);
            $results = [];
            while ($row = $stmt->fetch()) {
                $user = new CatalogueMapping($row);
                $results[] = $user;
            }
            return $results;
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la récupération du catalogue : " . $e->getMessage(), 0, $e);
        }
    }

    public function findBySlug(string $slug): ?CatalogueMapping
    {
        try {
            $sql = "SELECT * FROM catalogue WHERE slug = :slug LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':slug', $slug);
            $stmt->execute();

            $result = $stmt->fetch();

            if ($result) {
                return new CatalogueMapping($result);
            }
            return null;
        } catch (\Throwable $e) {
            throw new Exception(
                "Erreur lors de la récupération du véhicule par slug : " . $e->getMessage(),
                0,
                $e
            );
        }
    }
}

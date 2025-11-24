<?php

namespace model\manager;

use PDO;
use model\mapping\UserMapping;
use model\UserInterface;
use model\ManagerInterface;

class UserManager implements ManagerInterface, UserInterface
{
    protected PDO $pdo;
    protected string $table = 'users';

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Crée un nouvel utilisateur en base
     */
    public function create(UserMapping $user): bool
    {
        try {
            $sql = "INSERT INTO {$this->table} 
                (full_name, pseudo, email, phone, password, date_birth, gender)
                VALUES (:full_name, :pseudo, :email, :phone, :password, :date_birth, :gender)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':full_name', $user->getFullName());
            $stmt->bindValue(':pseudo', $user->getPseudo());
            $stmt->bindValue(':email', $user->getEmail());
            $stmt->bindValue(':phone', $user->getPhone());
            $stmt->bindValue(':password', $user->getPassword());
            $stmt->bindValue(':date_birth', $user->getDateBirth()?->format('Y-m-d'));
            $stmt->bindValue(':gender', $user->getGender());

            return $stmt->execute();
        } catch (\Throwable $e) {
            echo "Erreur SQL : " . $e->getMessage();
            return false;
        }
    }

    /**
     * Recherche par ID
     */
    public function findById(int $id): ?UserMapping
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row ? new UserMapping($row) : null;
    }

    /**
     * Recherche par email
     */
    public function findByEmail(string $email): ?UserMapping
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();

        return $row ? new UserMapping($row) : null;
    }

    /**
     * Récupère tous les utilisateurs
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        $results = [];
        while ($row = $stmt->fetch()) {
            $results[] = new UserMapping($row);
        }
        return $results;
    }

    /**
     * Met à jour un utilisateur
     */
    public function update(UserMapping $user): bool
    {
        if ($user->getId() === null) {
            return false;
        }
        try {
            $sql = "UPDATE {$this->table} SET
                full_name = :full_name,
                pseudo = :pseudo,
                email = :email,
                phone = :phone,
                password = :password,
                date_birth = :date_birth,
                gender = :gender,
                role = :role
                WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':full_name', $user->getFullName());
            $stmt->bindValue(':pseudo', $user->getPseudo());
            $stmt->bindValue(':email', $user->getEmail());
            $stmt->bindValue(':phone', $user->getPhone());
            $stmt->bindValue(':password', $user->getPassword());
            $stmt->bindValue(':date_birth', $user->getDateBirth());
            $stmt->bindValue(':gender', $user->getGender());
            $stmt->bindValue(':role', $user->getRole() ? 1 : 0, PDO::PARAM_INT);
            $stmt->bindValue(':id', $user->getId(), PDO::PARAM_INT);

            return $stmt->execute();
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Supprime un utilisateur
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Déconnexion
     */
    public function disconnect(): bool
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            session_destroy();

            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params["path"],
                    $params["domain"],
                    $params["secure"],
                    $params["httponly"]
                );
            }
            return true;
        }
        return false;
    }

    /**
     * Connexion
     */
    public function connect(array $tab): bool
    {
        if (!isset($tab['email'], $tab['password'])) {
            return false;
        }

        $email = trim($tab['email']);
        $password = $tab['password'];

        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();

        if (!$row) {
            return false;
        }

        $hash = $row['password'];

        if (!password_verify($password, $hash)) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['admin'] = true;

        return true;
    }


    public function generateHiddenId(): string
    {
        return bin2hex(random_bytes(16));
    }
}

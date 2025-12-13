<?php

namespace model\manager;

use PDO;
use Exception;
use model\mapping\UserMapping;
use model\UserInterface;
use model\ManagerInterface;


class UserManager implements ManagerInterface, UserInterface
{
    private PDO $pdo;


    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(array $data): ?UserMapping
    {
        try {
            $sql = "INSERT INTO users 
            (full_name, pseudo, email, email_token, email_token_expires, phone, password, date_birth, gender, 
             password_token, password_token_expires, is_verified, created_at)
        VALUES 
            (:full_name, :pseudo, :email, :email_token, :email_token_expires, :phone, :password, :date_birth, :gender,
             NULL, NULL, 0, NOW())";

            $emailTokenExpires = new \DateTime('+24 hours');
            $emailToken = bin2hex(random_bytes(32));

            // Créer l'objet mapping à partir des données
            $user = new UserMapping($data);
            $user->setEmailToken($emailToken);
            $user->setEmailTokenExpires($emailTokenExpires);

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':full_name', $user->getFullName());
            $stmt->bindValue(':pseudo', $user->getPseudo());
            $stmt->bindValue(':email', $user->getEmail());
            $stmt->bindValue(':email_token', $user->getEmailToken());
            $stmt->bindValue(':email_token_expires', $user->getEmailTokenExpires()?->format('Y-m-d H:i:s'));
            $stmt->bindValue(':phone', $user->getPhone());
            $stmt->bindValue(':password', $user->getPassword());
            $stmt->bindValue(':date_birth', $user->getDateBirth()?->format('Y-m-d'));
            $stmt->bindValue(':gender', $user->getGender());

            if ($stmt->execute()) {
                return $user;
            }
            return null;
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la création de l'utilisateur : " . $e->getMessage(), 0, $e);
        }
    }

    public function findAll(): array
    {
        try {
            $sql = "SELECT * FROM users ORDER BY id ASC";
            $stmt = $this->pdo->query($sql);
            $results = [];
            while ($row = $stmt->fetch()) {
                $passwordHash = $row['password'] ?? null;
                unset($row['password']);

                $user = new UserMapping($row);
                $user->setPasswordHash($passwordHash);

                $results[] = $user;
            }
            return $results;
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la récupération des utilisateurs : " . $e->getMessage(), 0, $e);
        }
    }

    public function updateProfile(array $data, int $id): bool
    {
        try {
            // Créer l'objet mapping à partir des données
            $user = new UserMapping($data);
            $user->setId($id);

            $sqlCheck = "SELECT id FROM users 
                     WHERE (email = :email OR pseudo = :pseudo OR phone = :phone) 
                     AND id != :id";
            $stmtCheck = $this->pdo->prepare($sqlCheck);
            $stmtCheck->execute([
                ':email' => $user->getEmail(),
                ':pseudo' => $user->getPseudo(),
                ':phone' => $user->getPhone(),
                ':id' => $user->getId()
            ]);

            if ($stmtCheck->fetch()) {
                echo "<p style='color:red;'>❌ Email, pseudo ou téléphone déjà utilisé.</p>";
                return false;
            }

            $sql = "UPDATE users SET
            full_name = :full_name,
            pseudo = :pseudo,
            email = :email,
            phone = :phone,
            date_birth = :date_birth,
            gender = :gender";

            if ($user->getPassword() !== null && $user->getPassword() !== '') {
                $sql .= ", password = :password";
            }

            $sql .= " WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':full_name', $user->getFullName());
            $stmt->bindValue(':pseudo', $user->getPseudo());
            $stmt->bindValue(':email', $user->getEmail());
            $stmt->bindValue(':phone', $user->getPhone());
            $stmt->bindValue(':date_birth', $user->getDateBirth()?->format('Y-m-d'));
            $stmt->bindValue(':gender', $user->getGender());

            if ($user->getPassword() !== null && $user->getPassword() !== '') {
                $stmt->bindValue(':password', $user->getPassword());
            }

            $stmt->bindValue(':id', $user->getId(), PDO::PARAM_INT);

            return $stmt->execute();
        } catch (\Throwable $e) {
            echo "<p style='color:red;'>❌ Erreur SQL : " . $e->getMessage() . "</p>";
            return false;
        }
    }


    public function delete(int $id): bool
    {
        try {
            $sql = "DELETE FROM users WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la suppression de l'utilisateur : " . $e->getMessage(), 0, $e);
        }
    }



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

    public function connect(array $tab): ?UserMapping
    {
        if (!isset($tab['email'], $tab['password'])) {
            return null;
        }

        try {
            $email = trim($tab['email']);
            $password = $tab['password'];

            $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':email' => $email]);
            $row = $stmt->fetch();

            if (!$row) {
                return null;
            }

            $hash = $row['password'];

            if (!password_verify($password, $hash)) {
                return null;
            }

            if ((int)$row['is_verified'] === 0) {
                return null;
            }

            $user = new UserMapping($row);

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user->getId();
            $_SESSION['role'] = (int) $user->getRole();

            return $user;
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la connexion : " . $e->getMessage(), 0, $e);
        }
    }



    public function findById(int $id): ?UserMapping
    {
        try {
            $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();

            if (!$row) {
                return null;
            }

            $passwordHash = $row['password'] ?? null;
            unset($row['password']);

            $user = new UserMapping($row);
            $user->setPasswordHash($passwordHash);

            return $user;
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la recherche de l'utilisateur : " . $e->getMessage(), 0, $e);
        }
    }

    public function findByEmail(string $email): ?UserMapping
    {
        try {
            $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':email' => $email]);
            $row = $stmt->fetch();

            if (!$row) {
                return null;
            }

            $passwordHash = $row['password'] ?? null;
            unset($row['password']);

            $user = new UserMapping($row);
            $user->setPasswordHash($passwordHash);

            return $user;
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la recherche par email : " . $e->getMessage(), 0, $e);
        }
    }

    public function findByEmailForReset(string $email): ?UserMapping
    {
        try {
            $sql = "SELECT id, email, full_name, password, password_token, password_token_expires 
                FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':email' => $email]);
            $row = $stmt->fetch();

            if (!$row) {
                return null;
            }

            $passwordHash = $row['password'] ?? null;
            unset($row['password']);

            $user = new UserMapping($row);
            $user->setPasswordHash($passwordHash);

            return $user;
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la recherche par email : " . $e->getMessage(), 0, $e);
        }
    }

    public function generatePasswordResetToken(UserMapping $user): bool
    {
        try {
            $passwordTokenExpires = new \DateTime('+24 hours');
            $passwordToken = bin2hex(random_bytes(32));

            $sql = "UPDATE users 
                SET password_token = :password_token,
                    password_token_expires = :password_token_expires,
                    is_verified = 0
                WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':password_token', $passwordToken);
            $stmt->bindValue(':password_token_expires', $passwordTokenExpires->format('Y-m-d H:i:s'));
            $stmt->bindValue(':id', $user->getId(), PDO::PARAM_INT);

            if ($stmt->execute()) {
                $user->setPasswordToken($passwordToken);
                $user->setPasswordTokenExpires($passwordTokenExpires);
                return true;
            }
            return false;
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la génération du token : " . $e->getMessage(), 0, $e);
        }
    }

    public function findByPasswordToken(string $token): ?UserMapping
    {
        try {
            $sql = "SELECT * FROM users WHERE password_token = :token LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':token' => $token]);
            $row = $stmt->fetch();

            if (!$row) {
                return null;
            }

            if ($row['password_token_expires']) {
                $expires = new \DateTime($row['password_token_expires']);
                if ($expires < new \DateTime()) {
                    return null;
                }
            }

            $passwordHash = $row['password'] ?? null;
            unset($row['password']);

            $user = new UserMapping($row);
            $user->setPasswordHash($passwordHash);

            return $user;
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la recherche par token : " . $e->getMessage(), 0, $e);
        }
    }


    public function findByToken(string $token): ?UserMapping
    {
        $sql = "SELECT * FROM users WHERE email_token = :token LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':token', $token);
        $stmt->execute();
        $data = $stmt->fetch();
        return $data ? new UserMapping($data) : null;
    }

    public function confirmEmail(UserMapping $user): bool
    {
        $sql = "UPDATE users 
            SET is_verified = 1, 
                email_token = NULL, 
                email_token_expires = NULL 
            WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $user->getId(), \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getPasswordHashById(int $id): ?string
    {
        try {
            $sql = "SELECT password FROM users WHERE id = :id LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();

            return $row['password'] ?? null;
        } catch (\Throwable $e) {
            throw new Exception("Erreur lors de la récupération du mot de passe : " . $e->getMessage(), 0, $e);
        }
    }

    public function resetPassword(UserMapping $user): bool
    {
        $passwordHash = $user->getPassword();

        if ($passwordHash && strpos($passwordHash, '$2y$') !== 0) {
            $passwordHash = password_hash($passwordHash, PASSWORD_DEFAULT);
        }

        $sql = "UPDATE users 
            SET password = :password,
                password_token = NULL,
                password_token_expires = NULL,
                is_verified = 1
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':password', $passwordHash, \PDO::PARAM_STR);
        $stmt->bindValue(':id', $user->getId(), \PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function deleteAccount(UserMapping $user): bool
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $user->getId(), \PDO::PARAM_INT);
        return $stmt->execute();
    }
}

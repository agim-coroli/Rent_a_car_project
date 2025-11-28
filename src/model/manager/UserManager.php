<?php

namespace model\manager;

use PDO;
use model\mapping\UserMapping;
use model\UserInterface;
use model\ManagerInterface;
use model\Exception\ExceptionFr;

// quand on creer un objet UserManager il ressemble a ->

// UserManager Object {
//     pdo => PDO Object (connexion à la base de données)
//     table => "users"

//     // Méthodes disponibles :
//     create($user) => bool
//     findById($id) => UserMapping|null
//     findByEmail($email) => UserMapping|null
//     findAll() => array[UserMapping]
//     update($user) => bool
//     delete($id) => bool
//     connect($tab) => bool
//     disconnect() => bool
//     generateHiddenId() => string
// }

class UserManager implements ManagerInterface, UserInterface
{
    protected PDO $pdo;


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


            $sql = "INSERT INTO users 
    (full_name, pseudo, email, email_token, email_token_expires, phone, password, date_birth, gender)
    VALUES (:full_name, :pseudo, :email, :email_token, :email_token_expires, :phone, :password, :date_birth, :gender)";

            $emailTokenExpires = new \DateTime('+24 hours');
            $emailToken = bin2hex(random_bytes(32));

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

            return $stmt->execute();
        } catch (\Throwable $e) {
            throw new ExceptionFr("Erreur lors de la création de l'utilisateur : " . $e->getMessage(), 0, $e);
        }
    }
    /**
     * Récupère tous les utilisateurs
     */
    public function findAll(): array
    {
        try {
            $sql = "SELECT * FROM users ORDER BY id ASC";
            $stmt = $this->pdo->query($sql);
            $results = [];
            while ($row = $stmt->fetch()) {
                // Exclure le password de l'hydratation et le définir directement avec setPasswordHash
                $passwordHash = $row['password'] ?? null;
                unset($row['password']);

                $user = new UserMapping($row);
                $user->setPasswordHash($passwordHash);

                $results[] = $user;
            }
            return $results;
        } catch (\Throwable $e) {
            throw new ExceptionFr("Erreur lors de la récupération des utilisateurs : " . $e->getMessage(), 0, $e);
        }
    }


    /**
     * Met à jour le profil d'un utilisateur (utilisé par l'utilisateur lui-même - peut modifier le mot de passe)
     */
    public function updateProfile(UserMapping $user, bool $updatePassword = false): bool
    {
        if ($user->getId() === null) {
            return false;
        }
        try {
            $sql = "UPDATE users SET
                full_name = :full_name,
                pseudo = :pseudo,
                email = :email,
                phone = :phone,
                date_birth = :date_birth,
                gender = :gender";

            // Ajouter le password seulement si on veut le modifier
            if ($updatePassword && $user->getPassword() !== null) {
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

            if ($updatePassword && $user->getPassword() !== null) {
                $stmt->bindValue(':password', $user->getPassword());
            }

            $stmt->bindValue(':id', $user->getId(), PDO::PARAM_INT);

            return $stmt->execute();
        } catch (\Throwable $e) {
            throw new ExceptionFr("Erreur lors de la mise à jour du profil : " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Supprime un utilisateur
     */
    public function delete(int $id): bool
    {
        try {
            $sql = "DELETE FROM users WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (\Throwable $e) {
            throw new ExceptionFr("Erreur lors de la suppression de l'utilisateur : " . $e->getMessage(), 0, $e);
        }
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

            // Création de l'objet UserMapping avec toutes les données
            $user = new UserMapping($row);

            // Regénérer l'ID de session pour sécurité
            session_regenerate_id(true);

            // Stocker les infos en session
            $_SESSION['role'] = (int) $user->getRole();


            return $user;
        } catch (\Throwable $e) {
            throw new ExceptionFr("Erreur lors de la connexion : " . $e->getMessage(), 0, $e);
        }
    }


    /**
     * Recherche par ID
     */
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

            // Exclure le password de l'hydratation et le définir directement avec setPasswordHash
            $passwordHash = $row['password'] ?? null;
            unset($row['password']);

            $user = new UserMapping($row);
            $user->setPasswordHash($passwordHash);

            return $user;
        } catch (\Throwable $e) {
            throw new ExceptionFr("Erreur lors de la recherche de l'utilisateur : " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Recherche par email
     */
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

            // Exclure le password de l'hydratation et le définir directement avec setPasswordHash
            $passwordHash = $row['password'] ?? null;
            unset($row['password']);

            $user = new UserMapping($row);
            $user->setPasswordHash($passwordHash);

            return $user;
        } catch (\Throwable $e) {
            throw new ExceptionFr("Erreur lors de la recherche par email : " . $e->getMessage(), 0, $e);
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
}

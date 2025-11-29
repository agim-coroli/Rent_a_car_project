<?php

namespace service;

use PDO;
use model\manager\UserManager;
use model\mapping\UserMapping;

class UserService
{
    private UserManager $userManager;

    public function __construct(PDO $pdo)
    {
        $this->userManager = new UserManager($pdo);
    }

    public function getAllUsers(): array
    {
        try {
            $users = $this->userManager->findAll();
            return $this->formatResponse(true, 'Utilisateurs récupérés avec succès', $this->formatUsers($users));
        } catch (\Exception $e) {
            return $this->formatResponse(false, 'Erreur lors de la récupération des utilisateurs', null, $e->getMessage());
        }
    }

    public function getUserById(int $id): array
    {
        try {
            $user = $this->userManager->findById($id);

            if (!$user) {
                return $this->formatResponse(false, 'Utilisateur non trouvé', null, null, 404);
            }

            return $this->formatResponse(true, 'Utilisateur récupéré avec succès', $this->formatUser($user));
        } catch (\Exception $e) {
            return $this->formatResponse(false, 'Erreur lors de la récupération de l\'utilisateur', null, $e->getMessage());
        }
    }

    public function createUser(array $data): array
    {
        try {
            $required = ['full_name', 'pseudo', 'email', 'phone', 'password', 'date_birth', 'gender'];
            foreach ($required as $field) {
                if (!isset($data[$field]) || empty($data[$field])) {
                    return $this->formatResponse(false, "Le champ '$field' est requis", null, null, 400);
                }
            }

            if ($this->userManager->findByEmail($data['email'])) {
                return $this->formatResponse(false, 'Cet email est déjà utilisé', null, null, 409);
            }

            $user = new UserMapping($data);
            $success = $this->userManager->create($user);

            if ($success) {
                $createdUser = $this->userManager->findByEmail($data['email']);
                return $this->formatResponse(true, 'Utilisateur créé avec succès', $this->formatUser($createdUser), null, 201);
            }

            return $this->formatResponse(false, 'Erreur lors de la création de l\'utilisateur', null);
        } catch (\Exception $e) {
            return $this->formatResponse(false, 'Erreur lors de la création', null, $e->getMessage(), 400);
        }
    }

    public function updateUser(int $id, array $data): array
    {
        try {
            $user = $this->userManager->findById($id);

            if (!$user) {
                return $this->formatResponse(false, 'Utilisateur non trouvé', null, null, 404);
            }

            foreach ($data as $key => $value) {
                $setter = 'set' . str_replace('_', '', ucwords($key, '_'));
                if (method_exists($user, $setter) && $key !== 'id') {
                    try {
                        $user->$setter($value);
                    } catch (\Exception $e) {
                        return $this->formatResponse(false, "Erreur de validation pour '$key'", null, $e->getMessage(), 400);
                    }
                }
            }

            $success = $this->userManager->update($user);

            if ($success) {
                $updatedUser = $this->userManager->findById($id);
                return $this->formatResponse(true, 'Utilisateur mis à jour avec succès', $this->formatUser($updatedUser));
            }

            return $this->formatResponse(false, 'Erreur lors de la mise à jour', null);
        } catch (\Exception $e) {
            return $this->formatResponse(false, 'Erreur lors de la mise à jour', null, $e->getMessage());
        }
    }

    public function deleteUser(int $id): array
    {
        try {
            $user = $this->userManager->findById($id);

            if (!$user) {
                return $this->formatResponse(false, 'Utilisateur non trouvé', null, null, 404);
            }

            $success = $this->userManager->delete($id);

            if ($success) {
                return $this->formatResponse(true, 'Utilisateur supprimé avec succès', null);
            }

            return $this->formatResponse(false, 'Erreur lors de la suppression', null);
        } catch (\Exception $e) {
            return $this->formatResponse(false, 'Erreur lors de la suppression', null, $e->getMessage());
        }
    }

    private function formatUser(UserMapping $user): array
    {
        return [
            'id' => $user->getId(),
            'full_name' => $user->getFullName(),
            'pseudo' => $user->getPseudo(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'date_birth' => $user->getDateBirth()?->format('Y-m-d'),
            'gender' => $user->getGender(),
            'role' => $user->getRole(),
            'created_at' => $user->getCreatedAt()?->format('Y-m-d H:i:s')
        ];
    }

    private function formatUsers(array $users): array
    {
        return array_map(function ($user) {
            return $this->formatUser($user);
        }, $users);
    }

    private function formatResponse(bool $success, string $message, ?array $data = null, ?string $error = null, int $statusCode = 200): array
    {
        $response = [
            'success' => $success,
            'message' => $message,
            'status_code' => $statusCode
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        if ($error !== null) {
            $response['error'] = $error;
        }

        return $response;
    }

    public function sendJsonResponse(array $response): void
    {
        http_response_code($response['status_code']);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}

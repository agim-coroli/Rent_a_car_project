# 🏗️ Architecture et Cheminement de l'API

Ce document explique ce qui se passe en temps réel quand un utilisateur demande la liste de tous les utilisateurs.

---

## 📋 Scénario : Récupérer la liste de tous les utilisateurs

**Requête :** `GET http://localhost/projet_rent_a_car/public/?api=users`

---

## 🔄 Cheminement complet

### **1. L'utilisateur fait la requête**

L'utilisateur (React, Postman, navigateur) envoie :
```
GET /?api=users
```

Le routeur détecte `?api=users` et crée un objet `UserService`.

---

### **2. UserService - Point d'entrée de l'API**

**Fichier :** `src/service/UserService.php`

**Quand on crée un objet de UserService, il ressemble à ça :**

```php
UserService Object {
    userManager => UserManager Object {
        pdo => PDO Object
        table => "users"
    }
    
    // Méthodes disponibles :
    getAllUsers() => array (JSON formaté)
    getUserById($id) => array (JSON formaté)
    createUser($data) => array (JSON formaté)
    updateUser($id, $data) => array (JSON formaté)
    deleteUser($id) => array (JSON formaté)
    sendJsonResponse($response) => void
}
```

**Ce qui se passe en temps réel :**

```php
// Le routeur appelle :
$response = $userService->getAllUsers();

// Dans UserService->getAllUsers() :
public function getAllUsers(): array
{
    // 1. UserService demande à UserManager de récupérer les données
    $users = $this->userManager->findAll();
    // ↑ $users contient maintenant un tableau d'objets UserMapping
    
    // 2. UserService formate les objets en tableaux PHP pour JSON
    $formatted = $this->formatUsers($users);
    // ↑ Transforme [UserMapping, UserMapping] en [['id'=>1, ...], ['id'=>2, ...]]
    
    // 3. UserService crée une réponse standardisée
    return $this->formatResponse(true, 'Utilisateurs récupérés avec succès', $formatted);
    // ↑ Retourne un tableau avec success, message, status_code, data
}
```

**Rôle de UserService :**
- Utilise UserManager pour récupérer les données
- Transforme les objets PHP en format JSON
- Ajoute les codes HTTP et messages
- Prépare la réponse pour le frontend

---

### **3. UserManager - Accès à la base de données**

**Fichier :** `src/model/manager/UserManager.php`

**Quand on crée un objet UserManager, il ressemble à ça :**

```php
UserManager Object {
    pdo => PDO Object (connexion à la base de données)
    table => "users"
    
    // Méthodes disponibles :
    findAll() => array[UserMapping]
    findById($id) => UserMapping|null
    findByEmail($email) => UserMapping|null
    create($user) => bool
    update($user) => bool
    delete($id) => bool
}
```

**Ce qui se passe en temps réel :**

```php
// UserService appelle :
$users = $this->userManager->findAll();

// Dans UserManager->findAll() :
public function findAll(): array
{
    // 1. UserManager prépare la requête SQL
    $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
    // ↑ $this->table = "users"
    
    // 2. UserManager exécute la requête SQL
    $stmt = $this->pdo->query($sql);
    // ↑ Exécute : SELECT * FROM users ORDER BY id DESC
    
    // 3. UserManager transforme chaque ligne SQL en objet UserMapping
    $results = [];
    while ($row = $stmt->fetch()) {
        // $row = ['id' => 1, 'full_name' => 'John Doe', 'email' => '...', ...]
        $results[] = new UserMapping($row);
        // ↑ Crée un objet UserMapping pour chaque ligne
    }
    
    // 4. UserManager retourne le tableau d'objets UserMapping
    return $results;
    // ↑ Retourne [UserMapping, UserMapping, UserMapping, ...]
}
```

**Rôle de UserManager :**
- Exécute les requêtes SQL
- Transforme les résultats SQL en objets UserMapping
- Retourne des objets PHP (pas de JSON)

---

### **4. UserMapping - Représentation d'un utilisateur**

**Fichier :** `src/model/mapping/UserMapping.php`

**Quand on crée un objet de UserMapping, il ressemble à ça :**

```php
UserMapping Object {
    id => 1
    full_name => "John Doe"
    pseudo => "johndoe"
    email => "john@example.com"
    phone => "+32490120095"
    password => "$2y$10$..." (hashé)
    date_birth => DateTime Object (1990-01-15)
    gender => "Masculin"
    role => false
    created_at => DateTime Object (2024-01-01 10:00:00)
}
```

**Ce qui se passe en temps réel :**

```php
// UserManager crée un UserMapping :
$row = $stmt->fetch();
// $row = [
//     'id' => 1,
//     'full_name' => 'John Doe',
//     'pseudo' => 'johndoe',
//     'email' => 'john@example.com',
//     'phone' => '+32490120095',
//     'password' => '$2y$10$...',
//     'date_birth' => '1990-01-15',
//     'gender' => 'Masculin',
//     'role' => 0,
//     'created_at' => '2024-01-01 10:00:00'
// ]

$user = new UserMapping($row);
// ↑ Le constructeur appelle automatiquement hydrate()

// Dans AbstractMapping->hydrate() :
protected function hydrate(array $datas)
{
    foreach ($datas as $setter => $value) {
        // 'full_name' → 'setFullName'
        $setterName = "set" . str_replace("_", "", ucwords($setter, '_'));
        
        if (method_exists($this, $setterName)) {
            // Appelle setFullName('John Doe')
            // Appelle setEmail('john@example.com')
            // Appelle setDateBirth('1990-01-15') → convertit en DateTime
            // etc.
            $this->$setterName($value);
        }
    }
}

// Résultat : Un objet UserMapping avec toutes les propriétés remplies
```

**Rôle de UserMapping :**
- Représente un utilisateur en objet PHP
- Valide les données via les setters
- Fournit des getters pour accéder aux données
- Convertit automatiquement les dates en objets DateTime

---

### **5. Retour en sens inverse**

#### **5.1. Base de données → UserMapping**

```php
// La base de données retourne des lignes SQL
Ligne 1: ['id' => 1, 'full_name' => 'John Doe', ...]
Ligne 2: ['id' => 2, 'full_name' => 'Jane Doe', ...]
Ligne 3: ['id' => 3, 'full_name' => 'Bob Smith', ...]

// UserManager transforme chaque ligne en UserMapping
UserMapping Object { id: 1, full_name: 'John Doe', ... }
UserMapping Object { id: 2, full_name: 'Jane Doe', ... }
UserMapping Object { id: 3, full_name: 'Bob Smith', ... }
```

#### **5.2. UserMapping → UserManager**

```php
// UserManager retourne un tableau d'objets UserMapping
return [
    UserMapping Object { id: 1, email: 'john@...', ... },
    UserMapping Object { id: 2, email: 'jane@...', ... },
    UserMapping Object { id: 3, email: 'bob@...', ... }
];
```

#### **5.3. UserManager → UserService**

```php
// UserService reçoit le tableau d'objets UserMapping
$users = $this->userManager->findAll();
// $users = [UserMapping, UserMapping, UserMapping]

// UserService transforme chaque objet en tableau PHP
private function formatUser(UserMapping $user): array
{
    return [
        'id' => $user->getId(),                    // 1
        'full_name' => $user->getFullName(),       // "John Doe"
        'pseudo' => $user->getPseudo(),           // "johndoe"
        'email' => $user->getEmail(),             // "john@example.com"
        'phone' => $user->getPhone(),             // "+32490120095"
        'date_birth' => $user->getDateBirth()?->format('Y-m-d'), // "1990-01-15"
        'gender' => $user->getGender(),           // "Masculin"
        'role' => $user->getRole(),               // false
        'created_at' => $user->getCreatedAt()?->format('Y-m-d H:i:s') // "2024-01-01 10:00:00"
    ];
    // Note : Le mot de passe n'est PAS inclus
}

// Résultat après formatUsers() :
[
    ['id' => 1, 'full_name' => 'John Doe', 'email' => 'john@...', ...],
    ['id' => 2, 'full_name' => 'Jane Doe', 'email' => 'jane@...', ...],
    ['id' => 3, 'full_name' => 'Bob Smith', 'email' => 'bob@...', ...]
]
```

#### **5.4. UserService crée la réponse JSON**

```php
// UserService crée une réponse standardisée
$response = $this->formatResponse(
    true,
    'Utilisateurs récupérés avec succès',
    $formatted
);

// Résultat :
[
    'success' => true,
    'message' => 'Utilisateurs récupérés avec succès',
    'status_code' => 200,
    'data' => [
        ['id' => 1, 'full_name' => 'John Doe', ...],
        ['id' => 2, 'full_name' => 'Jane Doe', ...],
        ['id' => 3, 'full_name' => 'Bob Smith', ...]
    ]
]
```

#### **5.5. UserService → Client (React/Navigateur)**

```php
// Le routeur envoie la réponse JSON
$userService->sendJsonResponse($response);

// Dans sendJsonResponse() :
http_response_code(200);
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
exit;
```

**Résultat final envoyé au client :**

```json
{
    "success": true,
    "message": "Utilisateurs récupérés avec succès",
    "status_code": 200,
    "data": [
        {
            "id": 1,
            "full_name": "John Doe",
            "pseudo": "johndoe",
            "email": "john@example.com",
            "phone": "+32490120095",
            "date_birth": "1990-01-15",
            "gender": "Masculin",
            "role": false,
            "created_at": "2024-01-01 10:00:00"
        },
        {
            "id": 2,
            "full_name": "Jane Doe",
            "pseudo": "janedoe",
            "email": "jane@example.com",
            "phone": "+32490120096",
            "date_birth": "1992-05-20",
            "gender": "Feminin",
            "role": false,
            "created_at": "2024-01-02 14:30:00"
        },
        {
            "id": 3,
            "full_name": "Bob Smith",
            "pseudo": "bobsmith",
            "email": "bob@example.com",
            "phone": "+32490120097",
            "date_birth": "1988-11-30",
            "gender": "Masculin",
            "role": false,
            "created_at": "2024-01-03 09:15:00"
        }
    ]
}
```

---

## 📊 Schéma récapitulatif

```
┌─────────────────────────────────────────────────────────────┐
│ UTILISATEUR                                                  │
│ GET /?api=users                                              │
└──────────────────────┬──────────────────────────────────────┘
                       ↓
┌─────────────────────────────────────────────────────────────┐
│ UserService->getAllUsers()                                   │
│ 1. Appelle $userManager->findAll()                           │
│ 2. Reçoit [UserMapping, UserMapping, ...]                    │
│ 3. Formate en tableaux PHP                                   │
│ 4. Crée la réponse JSON standardisée                         │
└──────────────────────┬──────────────────────────────────────┘
                       ↓
┌─────────────────────────────────────────────────────────────┐
│ UserManager->findAll()                                       │
│ 1. Exécute SELECT * FROM users                               │
│ 2. Récupère les lignes SQL                                   │
│ 3. Crée new UserMapping($row) pour chaque ligne              │
│ 4. Retourne [UserMapping, UserMapping, ...]                 │
└──────────────────────┬──────────────────────────────────────┘
                       ↓
┌─────────────────────────────────────────────────────────────┐
│ new UserMapping($row)                                        │
│ 1. Constructeur appelle hydrate()                            │
│ 2. Pour chaque clé : cherche le setter                       │
│ 3. Appelle le setter (validation + assignation)             │
│ 4. Crée l'objet avec toutes les propriétés                  │
└──────────────────────┬──────────────────────────────────────┘
                       ↓
┌─────────────────────────────────────────────────────────────┐
│ Base de données MySQL                                        │
│ Retourne les lignes de la table users                        │
└─────────────────────────────────────────────────────────────┘
                       ↓
┌─────────────────────────────────────────────────────────────┐
│ RETOUR (en sens inverse)                                    │
│ BDD → UserMapping → UserManager → UserService → JSON        │
└─────────────────────────────────────────────────────────────┘
```

---

## 🎯 Résumé des rôles

| Objet | Rôle | Entrée | Sortie |
|-------|------|--------|--------|
| **UserMapping** | Représente un utilisateur | Tableau SQL | Objet PHP avec propriétés |
| **UserManager** | Accès à la base de données | Requête SQL | Tableau d'objets UserMapping |
| **UserService** | Couche API et formatage | Tableau d'objets UserMapping | Tableau PHP formaté pour JSON |

---

## 💡 Points clés

1. **UserMapping** : Transforme une ligne SQL en objet PHP
2. **UserManager** : Exécute SQL et crée des objets UserMapping
3. **UserService** : Formate les objets UserMapping en JSON pour le frontend

4. **Flux de données :**
   - **Vers la BDD :** JSON → Tableaux PHP → Objets UserMapping → SQL
   - **Depuis la BDD :** SQL → Objets UserMapping → Tableaux PHP → JSON

5. **Création automatique :**
   - `UserService` crée `UserManager` dans son constructeur
   - `UserManager` crée des `UserMapping` depuis les résultats SQL
   - `UserMapping` s'hydrate automatiquement depuis un tableau

---

**Document créé pour le projet Rent A Car** 🚗

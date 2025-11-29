<?php

namespace model\Exception;

class ExceptionFr extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null)
    {
        $messageFr = $this->traduireMessage($message);
        parent::__construct($messageFr, $code, $previous);
    }

    private function traduireMessage(string $message): string
    {
        $sqlState = $this->extraireSqlState($message);
        
        $traductions = [
            "Undefined index" => "Indice non défini",
            "Division by zero" => "Division par zéro",
            "Connection failed" => "Échec de la connexion",
            "Call to undefined function" => "Appel à une fonction non définie",
            "Call to undefined method" => "Appel à une méthode non définie",
            "Class not found" => "Classe non trouvée",
            "Cannot access" => "Accès impossible",
            
            "SQLSTATE[23000]" => "Violation de contrainte d'intégrité",
            "SQLSTATE[23000]: Integrity constraint violation" => "Violation de contrainte d'intégrité",
            "SQLSTATE[42S02]" => "Table ou vue non trouvée",
            "SQLSTATE[42S22]" => "Colonne non trouvée",
            "SQLSTATE[42000]" => "Erreur de syntaxe SQL",
            "SQLSTATE[HY000]" => "Erreur générale",
            "SQLSTATE[28000]" => "Identifiant invalide",
            "SQLSTATE[3D000]" => "Base de données non trouvée",
            "SQLSTATE[23505]" => "Violation de contrainte d'unicité",
            "SQLSTATE[23503]" => "Violation de contrainte de clé étrangère",
            "SQLSTATE[22007]" => "Format de date invalide",
            "SQLSTATE[22001]" => "Données trop longues pour la colonne",
            "SQLSTATE[22003]" => "Valeur numérique hors limites",
            "SQLSTATE[08S01]" => "Erreur de communication avec le serveur",
            "SQLSTATE[HY093]" => "Nombre de paramètres invalide",
            
            "Duplicate entry" => "Cette entrée existe déjà",
            "Integrity constraint violation" => "Violation de contrainte d'intégrité",
            "Cannot add or update a child row" => "Impossible d'ajouter ou de mettre à jour une ligne enfant",
            "Cannot delete or update a parent row" => "Impossible de supprimer ou de mettre à jour une ligne parente",
            "Column" => "Colonne",
            "cannot be null" => "ne peut pas être nulle",
            "Table" => "Table",
            "doesn't exist" => "n'existe pas",
            "Unknown column" => "Colonne inconnue",
            "Access denied" => "Accès refusé",
            "Connection refused" => "Connexion refusée",
            "Too many connections" => "Trop de connexions",
            "Lost connection" => "Connexion perdue",
            "Query was empty" => "La requête était vide",
            "You have an error in your SQL syntax" => "Erreur de syntaxe SQL",
            "Table already exists" => "La table existe déjà",
            "Unknown database" => "Base de données inconnue",
            
            "for key" => "pour la clé",
            "PRIMARY" => "PRIMAIRE",
            "UNIQUE" => "UNIQUE",
            "FOREIGN KEY" => "CLÉ ÉTRANGÈRE",
        ];
        
        foreach ($traductions as $anglais => $francais) {
            if (stripos($message, $anglais) !== false) {
                $message = str_ireplace($anglais, $francais, $message);
            }
        }
        
        if ($sqlState) {
            $message = preg_replace('/SQLSTATE\[(\d+)\]/', 'Erreur SQL [$1]', $message);
        }
        
        $message = $this->nettoyerMessage($message);
        
        return $message;
    }
    
    private function extraireSqlState(string $message): ?string
    {
        if (preg_match('/SQLSTATE\[(\d+)\]/', $message, $matches)) {
            return $matches[1];
        }
        return null;
    }
    
    private function nettoyerMessage(string $message): string
    {
        $patterns = [
            '/\bfor key\b/i' => 'pour la clé',
            '/\bkey\s+[\'"]?(\w+)[\'"]?/i' => 'clé "$1"',
            '/\bcolumn\s+[\'"]?(\w+)[\'"]?/i' => 'colonne "$1"',
            '/\btable\s+[\'"]?(\w+)[\'"]?/i' => 'table "$1"',
        ];
        
        foreach ($patterns as $pattern => $replacement) {
            $message = preg_replace($pattern, $replacement, $message);
        }
        
        return $message;
    }
}





<?php

use model\manager\UserManager;

$manageComment = new UserManager($connectPDO);

$testData = [
    'email' => 'a@a.a',
    'password' => '$2y$10$WaPzsoEecCoUgb3QIxy3ceVKw0wI79PBPIpxis1vHz/e9cIJA8Pm.'
];

if ($manageComment->connect($testData)) {
    echo "Test OK : connexion réussie";
} else {
    echo "Test KO : email ou mot de passe incorrect";
}
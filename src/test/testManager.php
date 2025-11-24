<?php

// test

use model\manager\UserManager;
use model\mapping\UserMapping;



$manageComment = new UserManager($connectPDO);

$testData = [
    'email' => 'a@a.a',
    'password' => '$2y$10$WaPzsoEecCoUgb3QIxy3ceVKw0wI79PBPIpxis1vHz/e9cIJA8Pm.'
];
// $recup = $manageComment->create($newUser);
if ($manageComment->connect($testData)) {
    echo "Test OK : connexion réussie";
} else {
    echo "Test KO : email ou mot de passe incorrect";
}
// var_dump($recup);
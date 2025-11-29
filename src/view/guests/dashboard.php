<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body style="background-color: gray;">
    <?php
    require_once PATH . "/src/view/components/header.php";
    ?>
    <p>Accès refusé ❌ — vous devez être connecté pour voir le dashboard.</p>
    <a href="?pg=connexion">Se connecter</a>
</body>

</html>
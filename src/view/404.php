<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>

    <body style="background-color: brown;">

    <?php else: ?>

        <body>
        <?php endif; ?>
    <p>erreur 404</p>
    <a href="./">Accueil</a>
</body>

</html>
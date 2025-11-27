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
    <?php require_once "components/header.php" ?>
    <p>bienvenu sur connexion</p>


    <form method="post">

        <label for="email">email</label><br>
        <input type="email" name="email"><br>

        <label for="password">password</label><br>
        <input type="password" name="password"><br>


        <button type="submit">Connexion</button>

    </form>
</body>

</html>
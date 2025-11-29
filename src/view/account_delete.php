<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
    <title>Document</title>
</head>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 1) : ?>

    <body style="background-color: brown;">

    <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 0) : ?>

        <body style="background-color: green;">

        <?php else: ?>

            <body>
            <?php endif; ?>
            <?php require_once PATH . "/src/view/components/header.php"; ?>
            <?php require_once PATH . "/src/view/components/profil.php"; ?>
            <form method="post">
                <input type="hidden" name="delete_confirm" value="1">
                <Button>Etes vous sure de vouloir supprimer votre compte ?</Button>
                <p>(Action irréversible)</p>
            </form>
            </body>

</html>
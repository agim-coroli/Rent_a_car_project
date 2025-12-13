<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/style.css">

</head>


<body>
    <?php require_once PATH . "/src/view/components/header.php" ?>
    <h1>Réinitialisation du mot de passe</h1>
    <form method="post" action="?pg=reset_password">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">

        <label for="newPassword">Nouveau mot de passe</label><br>
        <input type="password" name="newPassword" id="newPassword" required><br><br>

        <label for="confirmNewPassword">Confirmer le nouveau mot de passe</label><br>
        <input type="password" name="confirmNewPassword" id="confirmNewPassword" required><br><br>

        <button type="submit">Changer mon mot de passe</button>
    </form>
</body>

</html>
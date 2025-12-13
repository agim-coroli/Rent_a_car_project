<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">

    <title>Document</title>
    <style>
        body {
            border: solid red;
        }
    </style>
</head>


<body>
    <?php require_once PATH . "/src/view/components/header.php"; ?>
    <form method="post">
        <label for="full_name">Nom complet</label><br>
        <input type="text" name="full_name" value="<?= htmlspecialchars($userToUpdate->getFullName()); ?>"><br>

        <label for="pseudo">Pseudo</label><br>
        <input type="text" name="pseudo" value="<?= htmlspecialchars($userToUpdate->getPseudo()); ?>"><br>

        <label for="email">Email</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($userToUpdate->getEmail()); ?>"><br>

        <label for="phone">Téléphone</label><br>
        <input type="text" name="phone" value="<?= htmlspecialchars($userToUpdate->getPhone()); ?>"><br>

        <label for="password">Mot de passe</label><br>
        <input type="password" name="password" value=""><br> <!-- on ne pré-remplit jamais un mot de passe -->

        <label for="date_birth">Date de naissance</label><br>
        <input type="date" name="date_birth"
            value="<?php echo htmlspecialchars($userToUpdate->getDateBirth()->format('Y-m-d')); ?>"><br>

        <label for="gender">Genre</label><br>
        <select name="gender" id="gender">
            <option value="">----</option>
            <option value="Masculin" <?= $userToUpdate->getGender() === 'Masculin' ? 'selected' : '' ?>>Masculin</option>
            <option value="Feminin" <?= $userToUpdate->getGender() === 'Feminin' ? 'selected' : '' ?>>Feminin</option>
        </select><br>


        <button type="submit">Changer mes informations</button>
    </form>

</body>

</html>
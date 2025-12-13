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
    <form method="post">
        <label for="email">email</label><br>
        <input type="email" name="email"><br>

        <button name="validation">changer mon mot de passe</button>
    </form>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<?php if (isset($_SESSION['role']) && $_SESSION['role'] === true) : ?>

    <body style="background-color: brown;">

    <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === false) : ?>

        <body style="background-color: green;">

        <?php else: ?>

            <body>
            <?php endif; ?>
            <?php require_once PATH . "/src/view/components/header.php" ?>

            <p>bienvenu sur connexion</p>


            <form method="post">

                <label for="email">email</label><br>
                <input type="email" name="email" id="email"><br>

                <label for="password">password</label><br>
                <input type="password" name="password" id="password"><br>

                <a href="?pg=forgot_password">Mot de passe oublié ?</a><br><br>

                <button type="submit">Connexion</button>

            </form>

            <button id="btn">Remplir automatiquement</button>
            <script>
                const btn = document.getElementById('btn');
                btn.addEventListener('click', () => {
                    document.getElementById('email').value = "agim.coroli.pro@gmail.com";
                    document.getElementById('password').value = "123456789";
                });
            </script>
            </body>

</html>
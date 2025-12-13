<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
<?php require_once PATH. "/src/view/components/header.php" ?>


    <p>inscription</p>
    <form method="post">
        <label for="full_name">full_name</label><br>
        <input type="text" name="full_name" id="full_name"><br>

        <label for="pseudo">pseudo</label><br>
        <input type="text" name="pseudo" id="pseudo"><br>

        <label for="email">email</label><br>
        <input type="email" name="email" id="email"><br>

        <label for="phone">phone</label><br>
        <input type="text" name="phone" id="phone"><br>

        <label for="password">password</label><br>
        <input type="password" name="password" id="password"><br>

        <label for="password_confirm">password confirmation</label><br>
        <input type="password" name="password_confirm" id="password_confirm"><br>

        <label for="date_birth">date_birth</label><br>
        <input type="date" name="date_birth" id="date_birth"><br>

        <label for="gender">gender</label><br>
        <select name="gender" id="gender">
            <option value="">----</option>
            <option value="Masculin">Masculin</option>
            <option value="Feminin">Feminin</option>
        </select><br>

        <button type="submit">Inscription</button>
    </form>

    <button id="btn">Remplir automatiquement</button>
    <script>
        const btn = document.getElementById('btn');
        btn.addEventListener('click', () => {
            document.getElementById('full_name').value = "agim coroli";
            document.getElementById('pseudo').value = "gimzed";
            document.getElementById('email').value = "agim.coroli.pro@gmail.com";
            document.getElementById('phone').value = "0477423505";
            document.getElementById('password').value = "123456789";
            document.getElementById('password_confirm').value = "123456789";
            document.getElementById('date_birth').value = "1993-03-11";
            document.getElementById('gender').value = "Masculin";
        });
    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php require_once "components/header.php" ?>

    <p>inscription</p>
    <form method="post">
        <label for="full_name">full_name</label><br>
        <input type="text" name="full_name"><br>

        <label for="pseudo">pseudo</label><br>
        <input type="text" name="pseudo"><br>

        <label for="email">email</label><br>
        <input type="email" name="email"><br>

        <label for="phone">phone</label><br>
        <input type="text" name="phone"><br>

        <label for="password">password</label><br>
        <input type="password" name="password"><br>

        <label for="date_birth">date_birth</label><br>
        <input type="date" name="date_birth"><br>

        <label for="gender">gender</label><br>
        <select name="gender" id="">
            <option value="">----</option>
            <option value="Masculin">Masculin</option>
            <option value="Feminin">Feminin</option>
        </select><br>

        <button type="submit">Inscription</button>

    </form>
</body>
</html>
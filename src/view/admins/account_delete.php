<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
    <title>Document</title>
</head>

<body>
    <?php require_once PATH . "/src/view/components/header.php"; ?>
    <form method="post">
        <input type="hidden" name="delete_confirm" value="1">
        <Button>Etes vous sure de vouloir supprimer votre compte ?</Button>
        <p>(Action irréversible)</p>
    </form>
</body>

</html>
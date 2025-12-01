<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
    <title>Document</title>
</head>

<body style="background-color: green;">
    <form method="POST" action="?pg=payment_confirm">
        <h2>Paiement</h2>
        <p>Véhicule : <?= htmlspecialchars($vehiculeToReserve->getMarque()) ?></p>
        <p>Du <?= $_SESSION['date_debut'] ?> à <?= $_SESSION['heure_debut'] ?>
            au <?= $_SESSION['date_fin'] ?> à <?= $_SESSION['heure_fin'] ?></p>

        <label for="card_number">Numéro de carte :</label>
        <input type="text" id="card_number" name="card_number" required>

        <label for="expiry">Expiration :</label>
        <input type="text" id="expiry" name="expiry" placeholder="MM/AA" required>

        <label for="cvv">CVV :</label>
        <input type="text" id="cvv" name="cvv" required>

        <button type="submit">Payer</button>
    </form>

</body>

</html>
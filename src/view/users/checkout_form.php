<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
    <title>Réservation véhicule</title>
    <style>
        .reservation-summary {
            max-width: 600px;
            margin: 2rem auto;
            padding: 1.5rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fdfdfd;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            font-family: Arial, sans-serif;
        }
        .reservation-summary h2 {
            margin-bottom: 1rem;
            font-size: 1.6rem;
            color: #333;
            text-align: center;
        }
        .reservation-summary p {
            margin: 0.5rem 0;
            font-size: 1.1rem;
            color: #555;
        }
        .highlight {
            font-weight: bold;
            color: #222;
        }
        .date-box {
            background: #eef2f7;
            padding: 0.8rem;
            border-radius: 6px;
            margin: 0.5rem 0;
        }
    </style>
</head>

<body>
    <?php require_once PATH . "/src/view/components/header.php"; ?>

    <div class="reservation-summary">


        <h2>Résumé de votre réservation</h2>

        <div class="date-box">
            <p><span class="highlight">Début :</span> <?= $dateDebut->format('d/m/Y H:i'); ?></p>
            <p><span class="highlight">Fin :</span> <?= $dateFin->format('d/m/Y H:i'); ?></p>
        </div>

        <p><span class="highlight">Durée :</span> <?= $nbJours; ?> jour(s)</p>
        <p><span class="highlight">Statut :</span> Réservé en attente de paiement</p>
        <form method="post">
            <button name="validation">Procéder au payement</button>
        </form>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
    <title>Document</title>
    <style>
        .catalogue-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5rem;
            padding: 1rem;
            width: 80%;
            margin: auto;
        }

        .catalogue-container * {
            color: black !important;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            background: #fff;
            flex: 1 1 300px;
            max-width: 300px;
            display: flex;
            flex-direction: column;
        }

        .card-image img {
            width: 100%;
            height: auto;
            display: block;
        }

        .card-content {
            padding: 1rem;
        }

        .card-content>div {
            margin-bottom: 1.5rem;
        }

        .card-content h1 {
            font-size: 1.2rem;
            margin: 0 0 0.5rem;
        }

        .card-content h2 {
            font-size: 1rem;
            margin: 0 0 1rem;
            color: #555;
        }

        .card-content a {
            margin: 0.25rem;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            background: #a7a7d6;
            color: #fff;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s ease;
        }

        .show_more:hover {
            background: #8181d3;

        }

        .btn_reserved:hover {
            background: #B6B6B6FF;

        }

        .btn_reserved {
            position: relative;
        }

        .btn_reserved::after {
            content: "Vous devez être inscrit !";
            position: absolute;
            top: -100%;
            left: -25%;
            background: #333;
            color: #fff;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }

        .btn_reserved:hover::after {
            opacity: 1;
        }
    </style>
</head>

<body>

    <?php require_once PATH . "/src/view/components/header.php" ?>
    <p>bienvenu sur catalogue</p>


    <div class="catalogue-container">
        <?php foreach ($allVehicule as $vehicule): ?>
            <div class="card">
                <div class="card-image">
                    <img src="assets/img/<?= htmlspecialchars($vehicule->getImage()) ?>"
                        onerror="this.onerror=null; this.src='assets/img/no-image-available.webp';">

                </div>

                <div class="card-content">
                    <h1><?= htmlspecialchars($vehicule->getMarque()) ?></h1>
                    <h2>Caution : <?= htmlspecialchars($vehicule->getCaution()) ?> €</h2>
                    <div>
                        <div>Navigateur GPS : <?= htmlspecialchars($vehicule->getNavigateurGps()) ?></div>
                        <div>Transmission : <?= htmlspecialchars($vehicule->getTransmission()) ?></div>
                        <div>Carburant : <?= htmlspecialchars($vehicule->getCarburant()) ?></div>
                        <div>Nombre de sièges : <?= htmlspecialchars($vehicule->getNombreSiege()) ?></div>
                    </div>
                    <a class="show_more" href="?pg=catalogue&slug=<?= $vehicule->getSlug() ?>">En savoir plus</a>
                    <a class="btn_reserved" href="?pg=inscription">Réserver</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</body>

</html>
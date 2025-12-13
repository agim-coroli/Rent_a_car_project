<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
    <title>test</title>
    <style>
        .car-detail-container {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            background: #fff;
            width: 80%;
            margin: 2rem auto;
            display: flex;
            gap: 0;
            overflow: hidden;
        }
        body{
            border: solid red;
        }
        .car-detail-container * {
            color: black !important;
        }

        .car-image {
            flex: 0 0 45%;
            background: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: clamp(240px, 35vh, 420px);
            border-right: 1px solid #ddd;
        }

        .car-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .car-info {
            flex: 1 1 55%;
            padding: clamp(1rem, 2.5vw, 2rem);
            display: flex;
            flex-direction: column;
            gap: clamp(0.75rem, 2vw, 1.25rem);
        }

        .car-title h1 {
            font-size: clamp(1.4rem, 2.4vw, 2.2rem);
            margin: 0 0 0.5rem;
        }

        .car-title h2 {
            font-size: clamp(1rem, 1.8vw, 1.25rem);
            margin: 0 0 0.75rem;
            color: #555;
        }

        .car-title h3 {
            font-size: clamp(0.95rem, 1.6vw, 1.15rem);
            margin: 0;
        }

        .car-info ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            gap: clamp(0.5rem, 1.5vw, 1rem);
        }

        .car-info ul li {
            background: #D4D4D4FF;
            border: 1px solid #eee;
            border-radius: 6px;
            padding: clamp(0.4rem, 1.2vw, 0.6rem) clamp(0.6rem, 1.8vw, 1rem);
            font-size: clamp(0.9rem, 1.4vw, 1rem);
            font-weight: bold;
        }

        .car-actions {
            margin-top: auto;
            display: flex;
            gap: 1rem;
            justify-content: flex-start;
            padding-left: 1rem;
        }

        .car-actions a {
            margin: 0.25rem 0;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            background: #a7a7d6;
            color: #fff;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s ease;
            font-size: clamp(0.9rem, 1.5vw, 1rem);
        }

        .car-actions a:hover {
            background: #8181d3;
        }

        @media (max-width: 992px) {
            .car-detail-container {
                flex-direction: column;
                width: 92%;
            }

            .car-image {
                flex: 0 0 auto;
                border-right: none;
                border-bottom: 1px solid #ddd;
                min-height: clamp(220px, 30vh, 360px);
            }

            .car-info ul li {
                flex: 1 1 100%;
            }

            .car-actions {
                margin-top: auto;
                display: flex;
                gap: 1rem;
                justify-content: center;
                padding: 0.5rem 0;
            }


            .car-actions a {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <?php require_once PATH . "/src/view/components/header.php"; ?>

    <div class="car-detail-container">
        <div class="car-image">
            <img src="assets/img/<?= htmlspecialchars($vehiculeDetails->getImage()) ?>"
                alt="<?= htmlspecialchars($vehiculeDetails->getMarque()) ?>"
                onerror="this.onerror=null; this.src='assets/img/no-image-available.webp';">


        </div>
        <div class="car-info">
            <div class="car-title">
                <h1><?= htmlspecialchars($vehiculeDetails->getMarque()) ?></h1>
                <h2><?= htmlspecialchars($vehiculeDetails->getDescription()) ?></h2>
                <h3>Caution : <?= htmlspecialchars($vehiculeDetails->getCaution()) ?> €</h3>
            </div>
            <ul>
                <li>Navigateur GPS : <?= htmlspecialchars($vehiculeDetails->getNavigateurGps()) ?></li>
                <li>Transmission : <?= htmlspecialchars($vehiculeDetails->getTransmission()) ?></li>
                <li>Carburant : <?= htmlspecialchars($vehiculeDetails->getCarburant()) ?></li>
                <li>Nombre de sièges : <?= htmlspecialchars($vehiculeDetails->getNombreSiege()) ?></li>
                <li>Année : <?= htmlspecialchars($vehiculeDetails->getAnnee()->format("Y")) ?></li>
                <li>Prix : <?= htmlspecialchars($vehiculeDetails->getPrix()) ?>€/jour</li>
                <li>Volume : <?= htmlspecialchars($vehiculeDetails->getVolume()) ?></li>
                <li>Dimension (LxLxH) : <?= htmlspecialchars($vehiculeDetails->getDimension()) ?></li>
                <li>Charge utile : <?= htmlspecialchars($vehiculeDetails->getChargeUtile()) ?></li>
                <li>Puissance : <?= htmlspecialchars($vehiculeDetails->getPuissance()) ?></li>
                <li>Air-co : <?= htmlspecialchars($vehiculeDetails->getAirCo()) ?></li>
                <li>Classe : <?= htmlspecialchars($vehiculeDetails->getClasseEnvironnementale()) ?></li>
                <li>Km inclus : <?= htmlspecialchars($vehiculeDetails->getKmInclus()) ?></li>
            </ul>
            <div class="car-actions">
                <a href="?pg=catalogue">FERMER</a>
            </div>
        </div>
    </div>
</body>

</html>
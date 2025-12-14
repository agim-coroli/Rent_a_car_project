<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre réservation</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>


        h1.page-title {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2rem;
            color: #FFFFFFFF;
        }

        .car-detail-container {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            margin: 1.5rem auto;
            max-width: 800px;
        }

        .car-info {
            padding: 2rem;
            border:3px solid white;
        }

        .car-image {
            background: #fafafa;
            display: flex;
            align-items: center;
            justify-content: center;
            border-top: 1px solid #eee;
            width: 75%;
        }

        .car-image img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }


        .car-title h2 {
            margin: 0;
            font-size: 1.6rem;
        }

        .car-title h3 {
            margin: 0.25rem 0;
            font-size: 1rem;
            color: #7f8c8d;
        }

        .reservation-info {
            border: 1px solid #eee;
            border-radius: 6px;
            padding: 1rem;
        }

        .reservation-info p {
            margin: 0.5rem 0;
            font-size: 0.95rem;
        }

        .car-specs {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .car-specs div {
            background: #ecf0f1;
            border-radius: 6px;
            padding: 0.6rem;
            font-size: 0.9rem;
            font-weight: 500;
            color: #2c3e50;
        }

        @media (max-width: 768px) {
            .car-detail-container {
                flex-direction: column;
            }

            .car-image {
                border-right: none;
                border-bottom: 1px solid #eee;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <?php require_once PATH . "/src/view/components/header.php"; ?>

    <h1 class="page-title">Vos réservation</h1>

    <?php if (empty($vehiculeReserved)): ?>
        <h2 style="text-align:center;">Vous n'avez aucune réservation</h2>
    <?php else: ?>
        <?php foreach ($vehiculeReserved as $item): ?>
            <?php $vehicule = $item['vehicule']; ?>
            <?php $reservation = $item['reservation']; ?>

            <div class="car-detail-container">
                <div class="car-info">
                    <div class="car-title">
                        <h2><?= htmlspecialchars($vehicule->getMarque()) ?></h2>
                        <h3><?= htmlspecialchars($vehicule->getDescription()) ?></h3>
                    </div>

                    <div class="reservation-info">
                        <p><strong>Date fin :</strong> <?= (new DateTime($reservation->getDateFin()))->format("d/m/Y H:i") ?></p>
                        <span><strong>Information complémentaire :</strong> Vous risquez une lourde amande si vous ne remettez pas le véhicule dans les délais</span>
                    </div>
                </div>

                <!-- Image déplacée en bas à la place de car-specs -->
                <div class="car-image">
                    <img src="assets/img/<?= htmlspecialchars($vehicule->getImage()) ?>"
                        alt="<?= htmlspecialchars($vehicule->getMarque()) ?>"
                        onerror="this.onerror=null; this.src='assets/img/no-image-available.webp';">
                </div>
            </div>

        <?php endforeach; ?>
    <?php endif; ?>
</body>

</html>
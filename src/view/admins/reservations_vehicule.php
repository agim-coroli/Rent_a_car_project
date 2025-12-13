<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">
    <title>Réservation véhicule</title>
    <style>
        .reservation-container {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            background: #fff;
            width: 80%;
            margin: 2rem auto;
            display: flex;
            overflow: hidden;
        }
        body{
            border: solid red;
        }
        .reservation-container * {
            color: black !important;
        }

        .reservation-image {
            flex: 0 0 45%;
            background: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: clamp(240px, 35vh, 420px);
            border-right: 1px solid #ddd;
        }

        .reservation-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .reservation-info {
            flex: 1 1 55%;
            padding: clamp(1rem, 2.5vw, 2rem);
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .reservation-title h1 {
            font-size: clamp(1.4rem, 2.4vw, 2.2rem);
            margin: 0 0 0.5rem;
        }

        .reservation-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 1rem;
        }

        .reservation-form-debut,
        .reservation-form-fin {
            display: flex;
        }

        /* adzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz */
        .reservation-form label {
            font-weight: bold;
        }

        .reservation-form input {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .reservation-actions {
            margin-top: auto;
            display: flex;
            gap: 1rem;
            justify-content: flex-start;
        }

        .reservation-actions button,
        .reservation-actions a {
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

        .reservation-actions button:hover,
        .reservation-actions a:hover {
            background: #8181d3;
        }

        @media (max-width: 992px) {
            .reservation-container {
                flex-direction: column;
                width: 92%;
            }

            .reservation-image {
                border-right: none;
                border-bottom: 1px solid #ddd;
                min-height: clamp(220px, 30vh, 360px);
            }

            .reservation-actions {
                justify-content: center;
                padding: 0.5rem 0;
            }

            .reservation-actions button,
            .reservation-actions a {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <?php require_once PATH . "/src/view/components/header.php"; ?>

    <div class="reservation-container">
        <div class="reservation-image">
            <img src="assets/img/<?= htmlspecialchars($vehiculeToReserve->getImage()) ?>"
                alt="<?= htmlspecialchars($vehiculeToReserve->getMarque()) ?>"
                onerror="this.onerror=null; this.src='assets/img/no-image-available.webp';">
        </div>

        <div class="reservation-info">
            <div class="reservation-title">
                <h1><?= htmlspecialchars($vehiculeToReserve->getMarque()) ?></h1>
            </div>

            <!-- Formulaire de réservation -->
            <form class="reservation-form" method="post">

                <fieldset style="padding: 1.5rem; display: flex; align-items: center;">
                    <legend style="font-weight: bolder;">Date de début</legend>

                    <label style="font-size: 1.5rem;" for="date_debut">📆&nbsp;</label>
                    <input
                        type="date"
                        id="date_debut"
                        name="date_debut"
                        required
                        style="padding: .3rem; border: 1px solid #ddd; border-radius: 4px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                    &nbsp;&nbsp;&nbsp;

                    <label style="font-size: 1.5rem;" for="heure_fin">🕛&nbsp;</label>
                    <select
                        id="heure_fin"
                        name="heure_fin"
                        required
                        style="padding: .3rem; border: 1px solid #ddd; border-radius: 4px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                        <?php foreach ($datesAndHours as $hours): ?>
                            <option value="<?= $hours->getHoraire() ?>"><?= $hours->getHoraire() ?></option>
                        <?php endforeach; ?>
                    </select>
                </fieldset>


                <fieldset style="padding: 1.5rem; display: flex; align-items: center;">
                    <legend style="font-weight: bolder;">Date de fin</legend>

                    <label style="font-size: 1.5rem;" for="date_debut">📆&nbsp;</label>
                    <input type="date" id="date_fin" name="date_fin" required
                        style="padding: .3rem; border: 1px solid #ddd; border-radius: 4px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                    &nbsp;&nbsp;&nbsp;
                    <label style="font-size: 1.5rem;" for="heure_fin">🕛&nbsp;</label>
                    <select id="heure_debut" name="heure_debut" required
                        style="padding: .3rem; border: 1px solid #ddd; border-radius: 4px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                        <?php foreach ($datesAndHours as $hours): ?>
                            <option value="<?= $hours->getHoraire() ?>"><?= $hours->getHoraire() ?></option>
                        <?php endforeach; ?>
                    </select>
                </fieldset>


                <div class="reservation-actions">
                    <button type="submit">Valider la réservation</button>
                    <a href="?pg=catalogue">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
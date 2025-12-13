<?php

namespace model\mapping;

use DateTime;
use model\AbstractMapping;
use Exception;

class CatalogueMapping extends AbstractMapping
{
    protected ?int $id = null;
    protected ?string $marque = null;
    protected ?string $slug = null;
    protected ?string $image = null;
    protected ?string $description = null;
    protected ?string $type = null;
    protected ?DateTime $annee = null;
    protected ?int $prix = null;
    protected ?int $caution = null;
    protected ?string $volume = null;
    protected ?string $dimension = null;
    protected ?string $chargeUtile = null;
    protected ?string $navigateur_gps = null;
    protected ?string $puissance = null;
    protected ?string $transmission = null;
    protected ?string $airco = null;
    protected ?string $carburant = null;
    protected ?int $nombre_siege = null;
    protected ?string $classe_environnementale = null;
    protected ?string $km_inclus = null;


    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }
    public function setMarque(?string $marque): self
    {
        if ($marque === null || trim($marque) === '') {
            throw new Exception("La marque ne peut pas être vide.");
        }
        $this->marque = trim($marque);
        return $this;
    }



    public function getImage(): ?string
    {
        return $this->image;
    }


    public function setImage(?string $image): self
    {
        if ($image === null || trim($image) === '') {
            throw new Exception("L'image ne peut pas être vide.");
        }
        $this->image = trim($image);
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }


    public function setSlug(?string $slug): self
    {
        if ($slug === null || trim($slug) === '') {
            throw new Exception("Le slug ne peut pas être vide.");
        }
        $this->slug = strtolower(trim($slug));
        return $this;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $description): self
    {
        if ($description === null || trim($description) === '') {
            throw new Exception("La description ne peut pas être vide.");
        }
        $this->description = trim($description);
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }
    public function setType(?string $type): self
    {
        $allowed = ['Voiture', 'Camionette'];
        if (!in_array($type, $allowed, true)) {
            throw new Exception("Le type doit être 'Voiture' ou 'Camionette'.");
        }
        $this->type = $type;
        return $this;
    }

    public function getAnnee(): ?DateTime
    {
        return $this->annee;
    }
    public function setAnnee($annee): self
    {
        if ($annee instanceof DateTime) {
            $this->annee = $annee;
        } elseif (is_string($annee) && $annee !== '') {
            $this->annee = new DateTime($annee);
        } else {
            $this->annee = null;
        }
        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }
    public function setPrix(?int $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    public function getCaution(): ?int
    {
        return $this->caution;
    }
    public function setCaution(?int $caution): self
    {
        $this->caution = $caution;
        return $this;
    }
    public function getVolume(): ?string
    {
        if ($this->volume === null) {
            return null;
        }

        $value = (float) $this->volume;

        $formatted = rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');

        return $formatted . ' m³';
    }
    
    public function setVolume($volume): self
    {
        if ($volume === null || trim($volume) === '') {
            throw new Exception("Le volume ne peut pas être vide.");
        }

        if (!preg_match('/^[0-9]+(\.[0-9]+)?$/', $volume)) {
            throw new Exception("Le volume doit être une valeur numérique décimale (ex: 0.30, 12.00).");
        }

        $this->volume = $volume;
        return $this;
    }


    public function getDimension(): ?string
    {
        return $this->dimension;
    }
    public function setDimension(?string $dimension): self
    {
        $this->dimension = trim($dimension);
        return $this;
    }

    public function getChargeUtile(): ?string
    {
        return $this->chargeUtile;
    }
    public function setChargeUtile(?string $chargeUtile): self
    {
        $this->chargeUtile = trim($chargeUtile);
        return $this;
    }

    public function getNavigateurGps(): ?string
    {
        return $this->navigateur_gps;
    }
    public function setNavigateurGps(?string $gps): self
    {
        $allowed = ['Inclus', 'Non-inclus'];
        if (!in_array($gps, $allowed, true)) {
            throw new Exception("Le GPS doit être 'Inclus' ou 'Non-inclus'.");
        }
        $this->navigateur_gps = $gps;
        return $this;
    }

    public function getPuissance(): ?string
    {
        return $this->puissance;
    }
    public function setPuissance(?string $puissance): self
    {
        if ($puissance === null || trim($puissance) === '') {
            throw new Exception("La puissance ne peut pas être vide.");
        }
        if (!preg_match('/^[0-9]+(\.[0-9]+)? kW$/', $puissance)) {
            throw new Exception("La puissance doit être exprimée en kW (ex: '100 kW').");
        }
        $this->puissance = $puissance;
        return $this;
    }

    public function getTransmission(): ?string
    {
        return $this->transmission;
    }
    public function setTransmission(?string $transmission): self
    {
        $allowed = ['Manuel', 'Automatique'];
        if (!in_array($transmission, $allowed, true)) {
            throw new Exception("La transmission doit être 'Manuel' ou 'Automatique'.");
        }
        $this->transmission = $transmission;
        return $this;
    }

    public function getAirco(): ?string
    {
        return $this->airco;
    }
    public function setAirco(?string $airco): self
    {
        $allowed = ['Oui', 'Non'];
        if (!in_array($airco, $allowed, true)) {
            throw new Exception("Airco doit être 'Oui' ou 'Non'.");
        }
        $this->airco = $airco;
        return $this;
    }

    public function getCarburant(): ?string
    {
        return $this->carburant;
    }
    public function setCarburant(?string $carburant): self
    {
        $this->carburant = trim($carburant);
        return $this;
    }

    public function getNombreSiege(): ?int
    {
        return $this->nombre_siege;
    }
    public function setNombreSiege(?int $nombre_siege): self
    {
        $this->nombre_siege = $nombre_siege;
        return $this;
    }

    public function getClasseEnvironnementale(): ?string
    {
        return $this->classe_environnementale;
    }
    public function setClasseEnvironnementale(?string $classe): self
    {
        $allowed = ['Euro 6b', 'Euro 6c', 'Euro 6d'];
        if (!in_array($classe, $allowed, true)) {
            throw new Exception("Classe environnementale invalide (Euro 6b, 6c ou 6d).");
        }
        $this->classe_environnementale = $classe;
        return $this;
    }

    public function getKmInclus(): ?string
    {
        return $this->km_inclus;
    }
    public function setKmInclus(?string $km_inclus): self
    {
        $this->km_inclus = trim($km_inclus);
        return $this;
    }
}

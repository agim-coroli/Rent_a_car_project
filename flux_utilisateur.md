# 🚗 Flux utilisateur (simplifié et efficace)

## 1. Avant réservation

**Utilisateur :** consulte le catalogue.

**Base de données :**
- catalogue → fiche technique du véhicule
- `vehicule_status.status = disponible`

👉 Le véhicule peut être réservé.

---

## 2. Demande de réservation

**Utilisateur :** clique sur "Réserver".

**Base de données :**
- Nouvelle ligne créée dans `reservations` avec `status = en_attente`
- `vehicule_status` reste disponible tant que ce n'est pas confirmé

---

## 3. Validation de la réservation

**Admin / système :** confirme la réservation.

**Base de données :**
- `reservations.status = confirmée`
- `vehicule_status.status = réservé`

👉 Le véhicule est bloqué pour la période choisie.

---

## 4. Début de la location

**Utilisateur :** vient chercher le véhicule.

**Base de données :**
- `reservations.status = en_cours`
- `vehicule_status.status = loué`

---

## 5. Fin de la location

**Utilisateur :** rend le véhicule.

**Base de données :**
- `reservations.status = terminée`
- `vehicule_status.status = disponible`

---

## 6. Cas exceptionnels

### Annulation
- `reservations.status = annulée`
- `vehicule_status.status = disponible`

### Panne / accident / maintenance
- `vehicule_status.status = indisponible`
- Réservations futures peuvent être annulées automatiquement
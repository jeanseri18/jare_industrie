# 🚦 FLUX GLOBAL — VUE SÉQUENTIELLE (résumé rapide)

1.  Opérateur de saisie -> crée fiche de souscription → soumet à comptabilité
2.  Comptabilité -> valide (ou rejette) _frais de dossier_ (active compte client)
    1.  Si client = _banque_ → comptabilité **ignore** frais de dossier → passe directement à DG
3.  Client -> paie _apport initial_ (tranches) ; compta enregistre et valide chaque tranche
    1.  Si apport = 100 % → fichier transmis au DG pour attribution
4.  DG -> attribue lot/îlot/numéro → système génère **attestation de réservation** (mail au client)
5.  Client -> paie _paiement projet_ (tranches) ; compta enregistre et valide
6.  Quand paiement projet = 100 % → DG valide dossier soldé → système génère **lettre d’attribution définitive** (mail au client pour retrait physique)
7.  Archivage / clôture du dossier

# 1) PHASE 1 — SOUSCRIPTION (Opérateur de saisie / Responsable Commercial possible)

### Objectif

Créer la fiche de souscription client avec toutes les infos nécessaires pour les étapes suivantes.

### Acteurs

*   Opérateur de saisie (principal)
*   Responsable Commercial (peut créer + corriger avant validation compta)

### Entrées (champs obligatoires)

*   Projet sélectionné (ID projet)
*   Type de client : {Mutuelle, Individuel, Individuel-Banque}
*   Si Mutuelle : Mutuelle sélectionnée (ID mutuelle)
*   Type de logement : {Villa basse, Villa duplex, Appartement, ...}
*   Variante logement (nb pièces, superficie) — si applicable
*   Informations client :
    *   Nom complet, date de naissance
    *   Téléphone (format international recommandé)
    *   Email
    *   Adresse (ville, quartier)
    *   Pièces jointes (scans PDF) : CNI / passeport, justificatif mutuelle si applicable, justificatif bancaire si financement
*   Champs calculés (auto) :
    *   Prix du logement (selon type + projet + mutuelle si applicable)
    *   % apport initial (param projet) → montant apport initial
    *   Frais de souscription (param projet / tarif mutuelle)
    *   Reste à payer total
*   Observations / notes internes (optionnel)

### Actions

1.  Saisie des champs. Les champs calculés sont automatiquement affichés.
2.  Validation côté opérateur (bouton _Soumettre à la comptabilité_).
    1.  Avant soumission, Responsable Commercial peut corriger les champs saisis (si demandé) — **tant que la compta n’a pas validé les frais**.
3.  Une fois _Soumettre_ cliqué → fiche verrouillée pour modification par l’opérateur (mais modifiable par Responsable Commercial tant que frais non validés).

### Sorties

*   Dossier enregistré en statut : **En attente validation frais** (sauf cas banque, voir règle ci-dessous)
*   Notification interne : Comptabilité / Responsable Commercial / DG (selon configuration)

### Messages automatiques (exemples)

*   À l’opérateur : “Fiche envoyée à la comptabilité — réf. SDB-2025-000123.”
*   À la compta : “Nouveau dossier en attente de validation — réf. SDB-2025-000123.”

# 2) PHASE 2 — VALIDATION DES FRAIS DE DOSSIER (Comptabilité)

### Objectif

Vérifier qu’un client a payé ses frais de dossier — et activer le compte client.

### Acteur

*   Comptabilité

### Entrées

*   Dossier en statut : _En attente validation frais_
*   Preuve de paiement (reçu) ou saisie manuelle
*   Mode de paiement : {Mobile Money, Virement, Cash, Tempérament, Crédit bancaire}
*   Numéro de reçu / référence transaction

### Actions

1.  Recherche / filtre : trouver le dossier par numéro / nom
2.  Vérifier justificatif (si upload) ou attendre confirmation bancaire
3.  Saisir :
    1.  Mode de paiement
    2.  Montant reçu (control : = frais ou montant partiel si tempérament ? voir règle en bas)
    3.  Numéro reçu / référence
4.  Valider : bouton _Valider frais de dossier_

### Conditions & décisions

*   **Si mode = Tempérament** : trait spécial — la compta peut accepter paiement partiel → dossier reste en _en attente apport initial_
*   **Si client = Individuel-Banque** : cette phase est SKIPPÉE automatiquement → dossier passe en _En attente attribution DG_ (voir règle bancaire)

### Sorties

*   Compte client activé (email d’invitation + lien pour définir mot de passe)
*   Dossier passe en statut : **En phase d’apport initial** (ou **En attente attribution DG** si banque)

### Messages automatiques (exemples)

*   Au client (email) : “Vos frais de dossier ont été reçus — votre compte est activé. Cliquez pour définir votre mot de passe.”
*   À l’opérateur : “Frais validés pour SDB-2025-000123.”
*   À Responsable Commercial (optionnel) : “Dossier validé par compta.”

# 3) PHASE 3 — PAIEMENT DE L’APPORT INITIAL (Client + Comptabilité)

### Objectif

Collecter et vérifier les versements constituant l’apport initial. L’apport doit atteindre 100% pour déclencher l’attribution.

### Acteurs

*   Client (paiement)
*   Comptabilité (enregistrement & validation)

### Entrées

*   Montant total apport calculé (issue phase 1)
*   Tranches payables (le système stocke historique)
*   Mode(s) de paiement utilisés

### Actions

1.  Le client paie via canal choisi (Mobile Money, Virement, Cash).
2.  Comptabilité enregistre chaque versement :
    1.  date, montant, mode, référence, reçu généré (PDF).
3.  Système recalcule : montant payé cumulés, % atteint, reste à payer.
4.  Si % >= 100 % → statut = **Apport soldé** → dossier transmis à DG pour attribution.
5.  Si % < 100 % → dossier reste en **Apport en cours**.

### Points de vigilance

*   **Règle pour versements partiels des frais** : si la compta accepte paiements partiels (tempérament), préciser seuil d’acceptation.
*   **Validation manuelle** : chaque ligne de paiement doit être validée par la compta avant d’être comptabilisée.

### Sorties

*   Reçu(s) disponibles dans espace client (PDF).
*   Notification interne si apport soldé : alerter DG (file d’atttribution).

### Messages automatiques (exemples)

*   Au client : “Merci — votre versement de 150 000 FCFA a été enregistré. Apport total : 75 %.”
*   À DG (si apport = 100 %) : “Nouveau dossier prêt pour attribution : SDB-2025-000123.”

# 4) PHASE 4 — ATTRIBUTION DU LOGEMENT (DG)

### Objectif

Attribuer officiellement un lot / îlot / numéro de villa au client dont l’apport est soldé (ou cas banque).

### Acteur

*   Directeur Général (DG)

### Entrées

*   Dossier(s) listés comme _Prêt pour attribution_
*   Données projet : carte des lots disponibles, état (réservé, disponible)
*   Historique client : apport, paiements, documents validés

### Modale / Formulaire d’attribution (champs)

*   Sélection lot (dropdown ou carte graphique) → Lot ID
*   Îlot (si applicable)
*   Numéro villa/appartement
*   Date d’attribution (auto = today)
*   Observations internes (optionnel)
*   Boutons : \[Attribuer & générer attestation\] | \[Annuler\]

### Actions

1.  DG choisit un lot disponible (système doit empêcher de prendre un lot déjà attribué).
2.  Valider attribution.
3.  Système :
    1.  marque lot comme _réservé / attribué_,
    2.  génère **Attestation de réservation (PDF)** avec : nom client, projet, lot/îlot/numéro, date, signature DG (digitale si prévue), référence.
    3.  envoie email au client avec attestation jointe.
    4.  change statut dossier en : **Attribué — En phase paiement projet**.

### Contraintes

*   Si plusieurs dossiers revendiquent même lot → mécanisme first-come or priorité (règle DG).
*   Historique d’attribution stocké (audit).

### Messages automatiques (exemples)

*   Au client : “Félicitations — votre lot B12 vous a été attribué. Télécharger l’attestation.”
*   À compta : “Dossier attribué — Activez le suivi paiement projet.”

# 5) PHASE 5 — PAIEMENT PROJET (Comptabilité + Client)

### Objectif

Enregistrer les paiements liés à la valeur du logement après attribution (peut être en tranches).

### Acteurs

*   Client
*   Comptabilité

### Entrées

*   Montant total du projet (après réduction mutuelle si applicable)
*   Plan de paiement / échéancier (si existant)
*   Paiements reçus / dossiers déjà enregistrés

### Actions

1.  Le client règle une ou plusieurs tranches.
2.  Comptabilité enregistre chaque transaction comme en Phase 3 (détails + reçu).
3.  Système calcule cumul, % total payé, reste.
4.  Quand % = 100 % → statut = **Projet Soldé** → dossier transmis au DG pour validation finale.

### Spécificités

*   Si financement bancaire : la banque peut payer tout ou partie ; si banque paie intégralement → dossier arrive soldé directement au DG.
*   Contrôle anti-fraude : vérification références bancaires si virements.

### Messages automatiques

*   Au client : “Votre paiement de X FCFA a été validé — vous avez réglé Y% du total.”
*   À DG (si dossier soldé) : “Dossier SDB-2025-000123 soldé — validation finale requise.”

# 6) PHASE 6 — VALIDATION FINALE & LETTRE D’ATTRIBUTION DÉFINITIVE (DG)

### Objectif

Vérifier que tout est en ordre et générer la lettre officielle clôturant le dossier.

### Acteur

*   DG

### Entrées

*   Dossier soldé (paiements ok)
*   Attestation attribuée (existante)
*   Historique complet (reçus, documents)

### Modal / Formulaire de validation finale (champs)

*   Vérification (checkbox) :
    *   Toutes les pièces sont complètes
    *   Paiements validés par la compta
    *   Lot / Îlot confirmé
*   Boutons : \[Confirmer et générer lettre\] | \[Refuser et renvoyer à compta\]

### Actions

1.  DG vérifie et coche les éléments de conformité.
2.  Valide : système génère **Lettre d’attribution définitive (PDF)**, inclut : références paiements, informations légales, signature DG.
3.  Envoi mail : “Votre lettre est prête — venez la récupérer au siège” (avec consignes).
4.  Dossier = **Clôturé** ; option pour archivage automatique.

### Messages automatiques

*   Au client : “Votre lettre d’attribution définitive est prête. Veuillez vous présenter au siège muni d’une pièce d’identité.”
*   Archivage : Dossier indexé et archivé dans l’historique.

# RÈGLES MÉTIERS ET DÉCISIONS SPÉCIALES (résumé précis)

### Mutuelle

*   **Création mutuelle** avant projet mutuelle.
*   Projet existant → activation tarif mutuelle possible (appliquée _aux nouvelles souscriptions seulement_).
*   Tarifs / % réduction impactent : prix logement, apport initial, montant des échéances.

### Financement bancaire

*   **Pas de frais**, pas d’apport initial.
*   Dossier passe en attribution DG dès insertion dans le système.
*   Paiement projet : généralement par la banque → si banque paye intégralement, dossier soldé → DG valide final.

### Rôle Responsable Commercial (précis)

*   Peut visualiser toutes fiches opérateur.
*   Peut corriger données **avant validation des frais de dossier par la compta**.
*   Peut créer une souscription (équipe commerciale sur le terrain), mais **ne peut pas valider paiements ni attribuer**.
*   Toute correction post-validation frais = requiert action de la compta (ou rejet + réouverture selon règle).

### Annulation / Rejet

*   Si la compta rejette la preuve paiement → statut = _Paiement rejeté_ → notification client + opération corrective (re-soumettre preuve de paiement).
*   Si opérateur a fait erreur après soumission et la compta a déjà validé → modification impossible ; corriger via note interne et procédure manuelle (annulation du dossier + création d’un nouveau dossier si besoin).

# CAS D’ERREUR / EXCEPTIONS & SOLUTIONS

1.  **Paiement double** (client envoie deux fois)
    1.  Comptabilité : enregistre les deux, contacte client. Système propose remboursement ou affectation en avance sur prochaines échéances.
2.  **Lot déjà attribué** (conflit)
    1.  Si DG tente d’attribuer un lot déjà réservé simultanément : jeu de verrouillage pessimiste — le premier validant conserve le lot ; l’autre doit choisir un autre lot. Le système affiche message d’erreur « Lot non disponible ».
3.  **Documents manquants**
    1.  Comptabilité refuse de valider si documents indispensables manquants ; notification client listant pièces manquantes.
4.  **Correction après validation compta**
    1.  Responsable Commercial ne peut modifier. Processus : demander annulation / ouverture dossier par DG ou compta selon nature.

# CHECKLISTS OPÉRATIONNELLES (pour QA & DEV)

### Pour Opérateur de saisie (avant soumission)

*   Projet sélectionné
*   Type client renseigné
*   Pièces jointes uploadées (CNI / mutuelle / justificatif bancaire)
*   Prix & apport auto calculés visibles
*   Observations internes renseignées si besoin

### Pour Comptabilité (avant validation frais)

*   Preuve de paiement reçue uploadée ou référence financière vérifiée
*   Montant reçu = frais (ou accepter temp.)
*   Numéro de reçu enregistré
*   Email d’activation envoyé au client

### Pour DG (avant attribution)

*   Apport = 100% (ou dossier banque)
*   Lots disponibles vérifiés sur plan
*   Attestation générée & envoyée

### Pour DG (avant validation finale)

*   Paiement projet = 100% validé
*   Reçus de tous les paiements disponibles
*   Lot confirmé, attestation existante
*   Lettre générée / signature

# EXEMPLES CONCRETS (mini-scénarios pour tester la logique)

### Scénario A — Client mutuelle SOTRA

1.  Opérateur crée fiche : Projet = “Mutuelle SOTRA 2025” (mutuelle reliée), prix réduit appliqué.
2.  Comptabilité valide frais (50 000 FCFA). Client reçoit lien activation.
3.  Client paie apport 100 % (600 000 FCFA) en deux tranches ; compta valide.
4.  DG attribue lot B12 → attestation générée.
5.  Client paie reste projet en 6 tranches ; compta valide chaque tranche.
6.  DG valide dossier soldé → lettre définitive générée.

### Scénario B — Client individuel financé par banque

1.  Responsable Commercial saisit dossier (type = banque) + docs bancaires.
2.  Fiche est soumise → **compta ne valide pas frais** (skippée) → dossier passe en attente DG.
3.  DG attribue lot directement.
4.  Banque effectue virment total (enregistrement par compta) → dossier soldé → DG génère lettre.

# FIN — Livrables recommandés pour la suite

*   Diagramme BPMN (processus complet) → utile pour dev & test.
*   Maquettes d’écrans pour chaque étape (formulaires + modales d’attribution / validation).
*   Spécifications API : endpoints pour créer souscription / valider paiement / générer attestation / générer lettre.
*   Scénarios de test QA (basés sur la checklist ci-dessus).
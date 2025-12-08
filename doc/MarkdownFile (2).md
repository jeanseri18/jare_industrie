# Jares — Processus et rôles consolidés (version finale)

Document de référence décrivant les rôles, leurs responsabilités et le workflow complet du système Jares Industries.

## 1) Rôles et responsabilités

### 1.1 Direction Générale (DG)

*   Rôle principal du système.
*   Dispose des **droits complets** : création et gestion des projets, mutuelles, utilisateurs, validations, rapports et supervision technique.
*   Peut exécuter toutes les actions de l’**Administrateur technique**.

### 1.2 Administrateur technique (optionnel)

*   Soutien à la DG pour la gestion **technique** du système.
*   Paramètre les options d’authentification, les intégrations (email, SMS, sauvegardes) et les connexions aux serveurs.
*   Gère la sécurité, les sauvegardes et la maintenance technique.

### 1.3 Opérateur de saisie

*   Enregistre les souscriptions des clients et les soumet pour validation.
*   N’a pas la possibilité de modifier une fiche après soumission.

### 1.4 Comptabilité

*   Enregistre les paiements (frais de dossier, apports, échéances).
*   Génère les reçus et gère la communication financière avec les clients.

### 1.5 Directeur commercial (DirCo)

*   Peut corriger les fiches verrouillées après soumission (avec historique des modifications).
*   Gère les anomalies de saisie ou les demandes spéciales.

### 1.6 Client

*   Accède à son compte en ligne pour consulter son dossier, ses paiements et ses documents (reçus, attestation, lettre d’attribution).
*   Peut communiquer avec le support via l’espace client.

## 2) Authentification et accès

### 2.1 Authentification par email

*   Tous les utilisateurs se connectent via **leur adresse email et mot de passe**.
*   Après saisie, le système envoie un **email de validation de session** contenant un lien sécurisé à usage unique.
*   Le lien est valable **5 minutes** ; au-delà, une nouvelle demande doit être effectuée.

### 2.2 Sécurité et droits

*   Le système utilise des **rôles hiérarchisés** : DG > Admin technique > DirCo > Comptabilité > Opérateur > Client.
*   Chaque action critique (création, validation, génération PDF) est **journalisée** avec date, auteur et détails avant/après modification.

## 3) Processus général

### Étape 1 — Saisie de la souscription (Opérateur)

1.  L’opérateur se connecte avec son email (validation par lien de session).
2.  Il sélectionne un projet actif et remplit la **fiche de souscription** :
    1.  Informations client (nom, téléphone, email, catégorie : Individuel, Diaspora, Association, Mutuelle).
    2.  Type de logement et nombre de pièces.
    3.  Le système calcule automatiquement la **valeur du logement**, l’**apport initial** et le **reste à payer**.
3.  S’il s’agit d’une mutuelle, les tarifs préférentiels et conditions spécifiques s’affichent.
4.  L’opérateur téléverse les documents PDF requis (CNI, justificatifs, etc.).
5.  Il soumet la fiche → le dossier devient **verrouillé** et un **numéro client** est généré automatiquement.
6.  Le dossier est transmis à la **Comptabilité** (ou à la **DG** en cas d’apport bancaire).

### Étape 2 — Frais de dossier (Comptabilité)

1.  La comptable consulte la **liste des clients en attente de paiement**.
2.  Elle enregistre le paiement : montant, date, mode (espèces, virement, mobile money, crédit bancaire, tempérament).
3.  Le système :
    1.  Génère un **reçu PDF**.
    2.  Envoie un **email de confirmation** au client (« Vos frais de dossier ont été enregistrés »).
    3.  Active le **compte client** pour qu’il puisse accéder à son espace personnel.
4.  Le dossier passe au statut **frais\_ok**.

### Étape 3 — Apport initial (Comptabilité)

1.  La comptabilité enregistre les paiements d’apport (en une ou plusieurs tranches).
2.  Le système met à jour automatiquement le **reste à payer** après chaque transaction.
3.  Chaque paiement déclenche :
    1.  Un **reçu PDF**.
    2.  Un **email automatique** au client.
4.  Une fois l’apport complet, le statut devient **apport\_ok**.
5.  Si le paiement est **cash**, le dossier est envoyé à la **DG pour validation (Attestation de Réservation)**.
6.  Si le paiement est **bancaire**, il passe directement à la **DG pour l’Attribution Définitive**.

### Étape 4 — Validation Interne : Attestation de Réservation (DG)

1.  La DG consulte la **liste des dossiers éligibles à la réservation** (paiement cash, apport complet).
2.  Elle renseigne :
    1.  Lot, Ilot, Numéro de villa.
3.  Le système génère une **Attestation de Réservation (PDF)** contenant :
    1.  Informations client et projet.
    2.  Détails du logement (lot, ilot, villa).
    3.  Montants payés et reste.
    4.  Signature numérique de la DG.
4.  La DG valide → le système :
    1.  Envoie un **email au client** avec lien de téléchargement de l’attestation.
    2.  Change le statut du dossier → **réservé**.

### Étape 5 — Suivi des paiements (Comptabilité)

1.  La comptabilité suit les paiements jusqu’au **solde total** du logement.
2.  À chaque paiement :
    1.  Un reçu est généré et envoyé au client.
3.  Une fois le paiement total effectué :
    1.  Le dossier passe au statut **soldé**.
    2.  Il est transmis à la DG pour la **Lettre d’Attribution Définitive**.

### Étape 6 — Validation Interne : Lettre d’Attribution Définitive (DG)

1.  La DG consulte les **dossiers soldés** (cash ou bancaires).
2.  Elle vérifie les informations du logement (lot, ilot, villa).
3.  Elle génère la **Lettre d’Attribution Définitive (PDF)**, incluant :
    1.  Coordonnées client et projet.
    2.  Informations logement.
    3.  Dates et montants de paiement.
    4.  QR code de vérification et signature numérique.
4.  Le client reçoit un **email** :

« Votre Lettre d’Attribution est prête. Vous pouvez venir la récupérer au siège. »

1.  Le dossier passe au statut **attribué**.

### Étape 7 — Corrections (DirCo)

1.  Le DirCo ouvre un dossier verrouillé pour correction.
2.  Il renseigne un **motif de correction obligatoire**.
3.  Il peut modifier certaines informations (catégorie, logement, échéancier, etc.).
4.  Le système enregistre un **audit complet** (avant/après, auteur, date).
5.  Les utilisateurs concernés reçoivent une notification interne.

### Étape 8 — Accès Client

1.  Le client reçoit un **email d’activation** après le paiement des frais de dossier.
2.  Il crée son mot de passe et accède à son espace personnel :
    1.  **Tableau de bord** : état de son dossier (Nouveau, Frais OK, Apport OK, Réservé, Soldé, Attribué).
    2.  **Paiements** : historique complet + reçus téléchargeables.
    3.  **Documents** : Attestation et Lettre d’Attribution téléchargeables.
    4.  **Notifications** : alertes de paiements et échéances.
    5.  **Support** : contact direct avec le service client (formulaire de message ou ticket).
3.  En cas d’apport bancaire, une bannière informe :

« Votre dossier suit le circuit bancaire. Vous serez notifié dès la génération de votre lettre d’attribution. »

1.  En cas de projet réservé à une mutuelle, un badge « Mutuelle X » s’affiche avec les conditions spécifiques.

## 4) Résumé des transitions

| Étape | Déclencheur | Statut suivant | Notification |
| --- | --- | --- | --- |
| Soumission opérateur | Validation fiche | submitted | — |
| Paiement frais de dossier | Comptabilité | frais_ok | Email d’activation client |
| Paiement apport complet | Comptabilité | apport_ok | Email DG + Comptabilité |
| Validation attestation | DG | réservé | Email client (attestation disponible) |
| Paiement total logement | Comptabilité | soldé | Email DG |
| Validation attribution | DG | attribué | Email client (lettre disponible) |

## 5) Cas particuliers

*   **Apport bancaire** : saute la phase « Frais de dossier » et passe directement à la DG (Attribution).
*   **Projets spécifiques Mutuelles** : visibles uniquement pour les adhérents ; la DG peut exiger un **visa Mutuelle** avant génération d’attestation/lettre.
*   **Correction DirCo** : nécessite motif et est tracée dans l’audit log.
*   **Authentification** : par email sécurisé, pas de code SMS.
*   **DG** cumule les droits techniques et administratifs.
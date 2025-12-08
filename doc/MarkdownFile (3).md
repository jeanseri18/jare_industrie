# 📘 DOCUMENT COMPLET — APPLICATION JARES INDUSTRIES

**Version finale intégrant le rôle exact du Responsable Commercial**

# 🟦 1. INTRODUCTION

Jares Industries est une entreprise de promotion immobilière qui commercialise différents types de logements (villas, duplex, appartements) dans plusieurs projets immobiliers.

Aujourd’hui, l’entreprise souhaite un système complet permettant de digitaliser tout le processus :

*   souscription des clients,
*   validation des paiements,
*   attribution des logements,
*   génération des documents,
*   suivi interne,
*   accès client.

L’application vise à :

*   réduire les erreurs administratives,
*   améliorer la fluidité du travail entre services,
*   garantir la traçabilité,
*   donner au client un accès clair à son dossier.

C’est une application **simple à utiliser**, mais couvrant un **processus complet**, structuré et sécurisé.

# 🟦 2. TYPES DE CLIENTS

L’application gère **deux grandes familles** de clients, avec leurs spécificités propres.

## 🟩 A. Client Mutuelle (groupe)

Ce type englobe :

*   Mutuelles (SOTRA, SIR, CNPS, etc.)
*   Associations
*   Syndicats
*   Groupes organisés
*   Entreprises partenaires

➡️ **Associations, syndicats et groupes = clients mutuelles.**

### Particularités :

*   Tous bénéficient de **tarifs préférentiels** (réduction sur le prix du logement).
*   Ils peuvent avoir des **projets dédiés**.
*   Ou être intégrés dans des **projets existants**, avec un tarif privilège.

### Règle métier essentielle :

➡️ **On crée d’abord la mutuelle → ensuite on peut créer un projet dédié.**  
OU  
➡️ On rend un projet existant compatible mutuelle (activation par DG).

## 🟦 B. Client Individuel

Deux types de parcours sont possibles :

### ✔️ 1) Individuel “classique”

*   Paie frais de dossier
*   Paie apport initial
*   Puis paie le projet

### ✔️ 2) Individuel financé par banque

*   Ne paie PAS les frais de dossier
*   Ne passe pas par l’apport initial  
    ➡️ **Il passe directement à la phase d’attribution DG**  
    (car la banque se substitue à lui dans le financement)

# 🟦 3. RÔLES ET RESPONSABILITÉS

## 👤 Client

*   Accède à son dossier : paiements, documents, statut, reçus.
*   Télécharge ses documents.
*   Consulte son avancement.

## 👩‍💻 Opérateur de saisie

*   Enregistre les nouvelles fiches clients.
*   Sélectionne projet + type client + type logement.
*   Dépose documents scannés.
*   Vérifie les valeurs générées automatiquement (apport, reste à payer).
*   Soumet la fiche à la comptabilité (après soumission → aucune modification possible).

## 🧮 Comptabilité

*   Valide paiements (frais, apport, projet).
*   Saisit numéro reçu, mode de paiement.
*   Génère reçus automatiques.
*   Transmet dossiers au DG selon phase.

## 📈 Responsable Commercial _(VERSION FINALE — TON CHOIX)_

Le Responsable Commercial a un rôle **opérationnel et correctif**, pas stratégique.

### Il peut :

*   Voir toutes les fiches enregistrées par les opérateurs.
*   **Corriger** certaines informations de la fiche _tant que la comptabilité n’a pas validé les frais de dossier_.
*   Créer un nouveau client (comme un opérateur de saisie).

### Il ne peut pas :

*   Valider des paiements
*   Modifier les montants financiers
*   Attribuer des logements
*   Générer des attestations ou lettres
*   Créer des projets
*   Créer des mutuelles
*   Gérer les utilisateurs

➡️ Il agit comme un **contrôleur qualité / superviseur de saisie**.

## 👑 Directeur Général (DG)

*   Véritable “super-administrateur fonctionnel”.
*   Attribue les logements (lot, îlot, numéro villa).
*   Valide les dossiers soldés.
*   Génère lettres définitives.
*   Crée et gère projets, utilisateurs, mutuelles.
*   Active les tarifs mutuelles sur projets existants.
*   Supervise tout le système.

## 🛠️ Administrateur technique

*   Paramètres système et sécurité.
*   Sauvegardes.
*   Support interne.

# 🟦 4. PROCESSUS COMPLET (DE A À Z)

Voici le parcours complet d’un client, expliqué simplement.

## 🔵 PHASE 1 — SOUSCRIPTION (Opérateur / Responsable Commercial)

1.  Choisir projet
2.  Choisir type client (mutuelle / individuel / banque)
3.  Choisir type logement
4.  Remplir fiche client
5.  Ajouter documents
6.  Vérifier montants calculés automatiquement
7.  Soumettre à la comptabilité

### Spécificité Responsable Commercial :

➡️ Peut corriger une fiche avant validation frais de dossier.  
➡️ Peut créer une nouvelle fiche lui-même.

## 🟡 PHASE 2 — FRAIS DE DOSSIER (Comptabilité)

*   Comptabilité vérifie le paiement
*   Saisit reçu
*   Valide
*   Active le compte client

Le client reçoit un email pour créer son mot de passe et accéder à son espace.

### Cas Individuel Banque

➡️ Ne passe PAS par cette phase  
➡️ Passe directement à l’attribution DG.

## 🟢 PHASE 3 — APPORT INITIAL (Comptabilité + client)

*   Paiements enregistrés tranche par tranche
*   Suivi du pourcentage (tranches payées / total)
*   Quand 100% → transmission DG pour attribution

### Cas Individuel Banque

➡️ N’a pas d’apport initial  
➡️ Passe directement à attribution.

## 🟣 PHASE 4 — ATTRIBUTION (DG)

*   Attribuer Lot / Ilot / N° villa
*   Générer attestation réservation
*   Notifier client

## 🟠 PHASE 5 — PAIEMENT PROJET (Comptabilité)

*   Paiements enregistrés par tranche
*   Reçus générés automatiquement
*   Quand tout est payé → dossier transmis DG

## 🔴 PHASE 6 — LETTRE D’ATTRIBUTION DÉFINITIVE (DG)

*   DG confirme le dossier
*   Génère lettre définitive
*   Notification client (il doit venir récupérer la lettre au siège)

# 🟦 5. ADMINISTRATION (DG & Admin technique)

### DG peut :

*   Créer projets
*   Créer mutuelles
*   Activer tarifs préférentiels sur projets existants
*   Créer utilisateurs
*   Modifier ou désactiver utilisateurs
*   Valider attributions
*   Valider dossiers soldés
*   Générer documents officiels

### Admin technique peut :

*   Paramétrer système
*   Gérer sécurité
*   Réinitialiser mots de passe internes
*   Sauvegarder données

# 🟦 6. INTERFACE CLIENT

Contenu visible par le client :

### ✔️ Tableau de bord

*   Statut dossier (phase actuelle)
*   Progression paiements :
    *   Frais de dossier
    *   Apport initial
    *   Paiement projet

### ✔️ Paiements

*   Reçus
*   Historique complet
*   Montants restants

### ✔️ Documents

*   Fiche souscription
*   Reçus
*   Attestation (si disponible)
*   Lettre définitive (si disponible)

### ✔️ Profil

*   Informations personnelles
*   Changer mot de passe

# 🟦 7. RÈGLES MÉTIER SPÉCIALES (Mutuelles & Banque)

### Mutuelles

*   Toujours créer mutuelle → ensuite projet.
*   Projet existant peut devenir “projet mutuelle”.

### Banque

*   Pas de frais de dossier
*   Pas d’apport
*   Attribution directe

# 🟦 8. SYNTHÈSE DES PARCOURS

CLIENT MUTUELLE → Frais dossier → Apport → Attribution → Paiement projet → Lettre finale  
CLIENT INDIVIDUEL NORMAL → Frais dossier → Apport → Attribution → Paiement projet → Lettre finale  
CLIENT INDIVIDUEL BANQUE → (Pas frais) → (Pas apport) → Attribution → Paiement projet → Lettre finale  

# 🟦 9. CONCLUSION

Ce document représente l’ensemble du fonctionnement de l’application Jares Industries, avec :

*   les rôles bien définis,
*   les types de clients,
*   les processus internes,
*   la logique des mutuelles,
*   l’exception bancaire,
*   et le rôle final du **Responsable Commercial**, conforme à ton exigence.
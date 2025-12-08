# 🧭 ARCHITECTURE COMPLÈTE DES ÉCRANS — JAres INDUSTRIES

## 🧑‍💼 1️⃣ DIRECTION GÉNÉRALE (DG / ADMIN COMPLET)

Le DG dispose de **tous les droits** : paramétrage, validation, création utilisateurs, gestion mutuelles, supervision comptable et technique.

### 🔐 ÉCRANS D’AUTHENTIFICATION

#### 1.1. Écran de connexion

*   **Objectif :** Connexion sécurisée via email + mot de passe.
*   **Champs :**
    *   Email
    *   Mot de passe
    *   Bouton “Se connecter”
    *   Lien “Mot de passe oublié” → modal “Envoyer lien de réinitialisation”.
*   **État post-login :**
    *   Email de validation → message “Vérifiez votre messagerie pour continuer.”
*   **UI/UX :**
    *   Logo Jares + visuel corporate (photo chantier, habitation).
    *   Layout minimaliste, fond blanc et bande latérale bleue.

### 🏠 1.2. DASHBOARD DG — Vue de supervision globale

#### 🎯 Objectif :

Centraliser toutes les informations clés de l’entreprise immobilière.

#### 🧱 Structure :

*   **Header haut :** Logo + Nom DG + Menu (Projets / Comptabilité / Mutuelles / Utilisateurs / Rapports / Paramètres)
*   **Corps (widgets en tuiles) :**
    *   **KPI financiers**
        *   Total encaissements du mois (FCFA)
        *   Moyenne apports / client
        *   % dossiers soldés
    *   **KPI opérationnels**
        *   Nombre de souscriptions actives
        *   Nombre de projets en cours
        *   Dossiers à valider (réservation / attribution)
    *   **Graphiques dynamiques**
        *   Courbe d’évolution des paiements (30 derniers jours)
        *   Répartition par type de client (camembert)
    *   **Alertes / notifications**
        *   Dossiers bloqués (mutuelle non validée, correction en attente)
        *   Échéances critiques
    *   **Top opérateurs**
        *   Classement des opérateurs par nombre de souscriptions validées
    *   **Top projets**
        *   Liste des projets avec taux de souscriptions / taux de paiement

#### 🔗 Navigation depuis le dashboard :

*   Clic sur “Projets actifs” → écran **Liste projets**
*   Clic sur “Dossiers à valider” → écran **Liste dossiers à réserver**
*   Clic sur “Utilisateurs actifs” → écran **Liste utilisateurs**

#### 🎨 UI/UX :

*   Couleurs sobres : bleu nuit (#0E2045), or (#D4A853), blanc.
*   Graphiques interactifs (hover, filtres période).
*   Résumé visuel clair avec CTA en haut :  
    ➕ _Créer un projet_ | 👥 _Créer un utilisateur_ | 📈 _Voir rapports_

### 🏗️ 1.3. GESTION DES PROJETS

#### Liste des projets

*   **Colonnes :** Nom, Localisation, Nb logements, % Apport, Frais, Statut.
*   **Filtres :** actif / archivé / spécifique mutuelle.
*   **Actions :**
    *   Créer projet
    *   Modifier projet
    *   Archiver
    *   Dupliquer → _nouveau projet spécifique mutuelle_.
*   **UI :** affichage sous forme de cartes projet + bouton “Créer”.

#### Formulaire création projet

*   **Onglets :**
    *   Informations générales
    *   Types de logement
    *   Conditions client / mutuelle
    *   Documents & validations
*   **Composants :**
    *   Slider pour % apport
    *   Input frais de souscription
    *   Checkbox “Projet spécifique mutuelle”
    *   Select mutuelle + champs remise personnalisée
*   **Validation :**
    *   Enregistrer brouillon / Publier
    *   Alerte si champ requis manquant

#### Détail projet

*   **Sections :**
    *   Informations générales
    *   Liste des souscriptions (tableau)
    *   Statistiques projet : apports, soldés, retards
    *   Graphiques : progression paiements
    *   Historique modifications
*   **Actions :** Modifier / Archiver / Exporter / Dupliquer.

### 🏢 1.4. MUTUELLES ET TARIFICATION

#### Liste mutuelles

*   **Colonnes :** Nom, Code, Nb clients affiliés, Règles prix.
*   **Actions :** Créer / Modifier / Activer / Désactiver.

#### Formulaire mutuelle

*   **Champs :**
    *   Nom / Code / Description
    *   Type de remise : % ou Montant
    *   Application : par projet / logement / pièces
    *   Table dynamique : \[Projet\] \[Type logement\] \[Remise\] \[Priorité\]
*   **Actions :**
    *   Tester prix (mini-simulateur)
    *   Enregistrer / Publier.

#### Visa Mutuelle (si projet spécifique)

*   Liste des dossiers nécessitant visa
*   Bouton : “Valider Visa” (case à cocher + signature numérique).

### 👥 1.5. GESTION DES UTILISATEURS

#### Liste des utilisateurs

*   **Colonnes :** Nom, Email, Rôle, Statut, Dernière connexion.
*   **Filtres :** Rôle / Activité / Date.
*   **Actions :** Créer / Modifier / Réinitialiser / Désactiver.

#### Formulaire création utilisateur

*   **Champs :**
    *   Nom / Email / Téléphone
    *   Rôle (DG / DirCo / Comptabilité / Opérateur / Client)
    *   Lien de validation par email (case à cocher)
*   **Actions :** Envoyer lien / Enregistrer.

#### Détail utilisateur

*   Profil complet
*   Historique des connexions
*   Actions : Réinitialiser mot de passe, désactiver compte.

### 📑 1.6. VALIDATION DES DOSSIERS

#### Dossiers à réserver

*   **Colonnes :** N° client, Projet, Apport, Statut, Mode paiement.
*   **Actions :** Ouvrir → Renseigner lot / ilot / villa → Générer attestation PDF.
*   **Aperçu attestation :**
    *   Montants versés, projet, signature DG.
*   **Actions :** Valider & Envoyer email client.

#### Dossiers à attribuer

*   **Colonnes :** Client, Projet, Montant total, Statut.
*   **Actions :** Ouvrir → Vérifier → Générer Lettre d’Attribution PDF.
*   **Aperçu lettre :**
    *   Coordonnées client, projet, QR code.
*   **Actions :** Valider & Envoyer.

### 📊 1.7. RAPPORTS ET JOURNAUX

#### Rapports

*   **Filtres :** Période / Projet / Statut / Catégorie.
*   **Graphiques :**
    *   Évolution paiements
    *   Répartition clients / mutuelles
    *   Taux de soldes par projet
*   **Export :** CSV / PDF.
*   **Actions :** Planifier envoi automatique par email.

#### Journal d’audit

*   **Colonnes :** Date, Utilisateur, Action, Avant / Après.
*   **Filtres :** Par entité, utilisateur, période.

### ⚙️ 1.8. PARAMÈTRES TECHNIQUES

*   SMTP / Email / Domaine / Sauvegardes.
*   Politique mot de passe (longueur, validité).
*   Timeout session.
*   Historique erreurs techniques.

## 👨🏾‍💻 2️⃣ OPÉRATEUR DE SAISIE — Vue simplifiée & productive

### Dashboard Opérateur

*   **KPI personnels :**
    *   Nombre de souscriptions créées / validées / en attente.
    *   Taux d’erreurs / corrections.
    *   Graphique “activité 7 derniers jours”.
*   **Actions rapides :**
    *   ➕ Nouvelle souscription
    *   🔍 Rechercher dossier
    *   📋 Voir mes souscriptions

### Workflow souscription

1.  **Sélection projet** → 2. **Fiche client** → 3. **Type logement**  
    → 4. **Détails & calculs** → 5. **Documents** → 6. **Récapitulatif & Soumission**

*   À chaque étape : bouton suivant + validation contextuelle.
*   Après soumission : message “Souscription verrouillée”.

## 💰 3️⃣ COMPTABILITÉ — Vue orientée flux financier

### Dashboard Comptabilité

*   **Widgets :**
    *   Total encaissé ce mois
    *   En attente (frais, apport, échéances)
    *   Nombre de dossiers soldés
    *   Graphique encaissements journaliers
    *   Tableau récap “Clients en retard”
*   **CTA :**
    *   ➕ Enregistrer un paiement
    *   📄 Voir rapports comptables

### Gestion des paiements

*   Liste “Frais de dossier en attente”
*   Liste “Apports en cours”
*   Liste “Clients soldés”

Chaque fiche paiement → Formulaire (montant, mode, date, reçu auto).

## 📋 4️⃣ DIRECTEUR COMMERCIAL

### Dashboard DirCo

*   **KPI :**
    *   Souscriptions corrigées
    *   Taux d’erreurs opérateurs
    *   Historique corrections
*   **Actions :**
    *   Ouvrir souscription
    *   Corriger
*   **Audit Log intégré**

## 👤 5️⃣ CLIENT (WEB + MOBILE)

### Dashboard Client

*   Barre de progression :  
    **Nouveau → Frais OK → Apport OK → Réservé → Soldé → Attribué**
*   Détails : montant payé / reste / prochaine échéance.
*   CTA : Télécharger documents / Voir paiements / Contacter support.

### Paiements

*   Historique transactions + reçus PDF.
*   Alertes échéances J-7 et J-1.

### Documents

*   Attestation réservation (PDF)
*   Lettre attribution (PDF)

### Support

*   Formulaire contact + historique messages.
# 👤 INTERFACE CLIENT — VERSION STRUCTURÉE ET MÉTIER

Application web/mobile permettant au client de **suivre son dossier de souscription, ses paiements et documents**, dans un environnement fluide et sécurisé.

L’accès est ouvert **après validation du paiement des frais de dossier** (mail de création de compte envoyé automatiquement).

## 🌐 1. TABLEAU DE BORD — PAGE D’ACCUEIL CLIENT

### 🎯 Objectif :

Offrir une **vue claire et synthétique** du statut du dossier client et de l’état d’avancement de ses paiements.

### 🔹 Bloc A — Informations générales

| Élément | Exemple | Description |
| --- | --- | --- |
| 👤 Nom du client | KONE Ibrahim | Nom complet |
| 🏗️ Projet | Résidence Cocody | Projet immobilier souscrit |
| 🏠 Type de logement | Villa duplex 5 pièces | Type de bien sélectionné |
| 🔢 Numéro client | CLT-2025-0458 | Numéro unique du dossier |
| 📊 Statut actuel | Phase : Apport initial (en cours) | État d’avancement du client |

**Règle métier :**

*   Si _Frais de dossier_ = validés → le client entre dans la phase _Apport initial_.
*   Si _Apport initial_ = soldé → la phase _Paiement projet_ devient accessible.
*   Si _Paiement projet_ = soldé → statut final : _Dossier clôturé – En attente de lettre définitive_.

### 🔹 Bloc B — Avancement financier

**Présentation :**  
Trois encadrés horizontaux (ou cartes empilées sur mobile), représentant les **phases de paiement** chronologiques.

| Phase | Montant total | Montant payé | Statut |  | Jauge |
| --- | --- | --- | --- | --- | --- |
| 🧾 Frais de dossier | 50 000 FCFA | 50 000 FCFA | ✅ Soldé |  | 100 % |
| 🪙 Apport initial | 600 000 FCFA | 450 000 FCFA | 🔄 En cours |  | 75 % |
| 💳 Paiement projet | 4 800 000 FCFA | 0 FCFA | 🔒 Non accessible |  | 0 % |

**Règle d’accès visuelle :**

*   Tant que **l’apport initial** n’est pas soldé → la section “Paiement projet” reste **grisée et verrouillée**.
*   Dès que **l’apport initial** atteint 100 % → un message s’affiche :

“✅ Apport initial soldé. Vous pouvez désormais effectuer vos paiements projet.”

**Sous-texte général (au bas du bloc) :**

“Vous avez réglé 500 000 FCFA sur un total de 5 450 000 FCFA, soit **9 % du montant global**.”

### 🔹 Bloc C — Documents et attestations

| Document | Statut | Action |
| --- | --- | --- |
| 📄 Fiche de souscription | Disponible | 📥 Télécharger |
| 🧾 Reçus de paiement | 3 reçus disponibles | 📥 Consulter |
| 🏠 Attestation de réservation | Non disponible (en attente DG) | 🔒 En cours de génération |
| 📜 Lettre d’attribution définitive | Non disponible | 🔒 Non accessible |

**Logique d’affichage :**

*   Les documents se débloquent automatiquement après validation DG ou comptabilité.
*   Les reçus s’ajoutent à chaque paiement validé.
*   L’attestation n’apparaît qu’après _attribution DG_.

### 🔹 Bloc D — Historique des paiements

| Date | Type de paiement | Montant | Mode | Statut |
| --- | --- | --- | --- | --- |
| 30/10/2025 | Apport initial (3ᵉ tranche) | 150 000 FCFA | Mobile Money | ✅ Validé |
| 20/10/2025 | Apport initial (2ᵉ tranche) | 150 000 FCFA | Banque | ✅ Validé |
| 05/10/2025 | Frais de dossier | 50 000 FCFA | Mobile Money | ✅ Validé |

**Sous-texte du bloc :**

“Tous vos paiements validés apparaissent ici avec leur reçu téléchargeable.”

## 💳 2. SECTION — HISTORIQUE DÉTAILLÉ DES PAIEMENTS

### 🎯 Objectif :

Permettre au client de revoir l’historique complet de ses paiements par phase.

| Type de paiement | Montant total | Montant payé | Nombre de tranches | Statut |
| --- | --- | --- | --- | --- |
| Frais de dossier | 50 000 FCFA | 50 000 FCFA | 1/1 | ✅ Soldé |
| Apport initial | 600 000 FCFA | 600 000 FCFA | 3/3 | ✅ Soldé |
| Paiement projet | 4 800 000 FCFA | 1 200 000 FCFA | 2/10 | 🔄 En cours |

**Sous-éléments à afficher :**

*   Pour chaque phase : bouton _“Voir le détail des tranches”_ → ouvre un tableau des versements.
*   Chaque ligne contient : date, montant, mode, reçu (📥).

**CTA :**  
📄 _Télécharger le récapitulatif global (PDF)_

## 📄 3. SECTION — DOCUMENTS OFFICIELS

### 🎯 Objectif :

Regrouper l’ensemble des documents administratifs liés au dossier du client.

| Type de document | Statut | Action |
| --- | --- | --- |
| Fiche de souscription | ✅ Disponible | 📥 Télécharger |
| Reçus de paiement | ✅ 3 reçus disponibles | 📥 Consulter |
| Attestation de réservation | 🕓 En attente DG | 🔒 Non disponible |
| Lettre d’attribution définitive | 🔒 En attente paiement total | Non accessible |

**Règle métier :**

*   L’attestation est générée uniquement après attribution DG.
*   La lettre définitive devient accessible uniquement après validation du paiement complet.

## ⚙️ 4. SECTION — GESTION DU COMPTE

### 🎯 Objectif :

Permettre au client de mettre à jour ses informations de profil et sécuriser son accès.

| Élément | Champs à prévoir |
| --- | --- |
| 👤 Informations personnelles | Nom, prénom, téléphone, email |
| 📍 Adresse | Ville, quartier (optionnel) |
| 🔐 Sécurité du compte | Changement de mot de passe |
| 🗓️ Historique de connexion | Dernière connexion + appareil |
| 🚪 Déconnexion | Bouton clair “Se déconnecter” |

**Message de validation :**

✅ _Vos informations ont été mises à jour avec succès._

## 📊 5. SECTION — SYNTHÈSE DU DOSSIER (Vue imprimable)

### 🎯 Objectif :

Donner une version complète du dossier sous format téléchargeable ou imprimable.

**Contenu :**

*   Informations client
*   Informations projet
*   Historique complet des paiements
*   Documents validés disponibles
*   Statut global du dossier

**CTA :**  
🖨️ _Imprimer mon dossier complet_  
📥 _Télécharger le récapitulatif PDF_

## 🎨 RECOMMANDATIONS UI/UX CLIENT

| Élément | Recommandation |
| --- | --- |
| Palette | Blanc / Bleu clair / Or (rappel visuel Jares Industries) |
| Design | Interface “clean”, cartes arrondies, pictos clairs (📄, 🏠, 💰, ✅) |
| Navigation principale | Tableau de bord / Paiements / Documents / Mon compte |
| Hiérarchie visuelle | 1er bloc = Statut dossier, 2e = Paiements, 3e = Documents |
| Typographie | Titres Poppins / Texte Inter |
| Feedback | Confirmation visuelle après actions (encadré vert avec ✅) |
| Mobile | Blocs empilés, menu inférieur persistent (“Accueil”, “Paiements”, “Documents”, “Profil”) |
CREATE TABLE paiements (
    id                BIGINT AUTO_INCREMENT PRIMARY KEY,
    dossier_id        BIGINT NOT NULL COMMENT 'lien vers la souscription',
    type              ENUM('FRAIS_DOSSIER','APPORT','PROJET') NOT NULL,
    montant           DECIMAL(10,2) NOT NULL,
    mode              ENUM('ESPECES','VIREMENT','MOBILE_MONEY','TEMPERAMENT','CREDIT_BANCAIRE') NOT NULL,
    reference         VARCHAR(100) COMMENT 'numéro reçu, ref banque, ID MM...',
    date_paiement     DATE NOT NULL,
    comptable_id      BIGINT NOT NULL COMMENT 'utilisateur Comptabilité ayant saisi',
    valide_at         DATETIME DEFAULT CURRENT_TIMESTAMP,
    cree_at           DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at        DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_paiements_dossier FOREIGN KEY (dossier_id) REFERENCES souscriptions(id),
    CONSTRAINT fk_paiements_comptable FOREIGN KEY (comptable_id) REFERENCES utilisateurs(id)
);



CREATE TABLE projets (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    nom                 VARCHAR(150) NOT NULL,
    localisation        VARCHAR(255),
    superficie          DECIMAL(10,2) COMMENT 'en m2',

    isduplex            BOOLEAN DEFAULT FALSE COMMENT 'est-ce un duplex ?',
    isterrains           BOOLEAN DEFAULT FALSE COMMENT 'est-ce un terrain ?',
    isvillabase            BOOLEAN DEFAULT FALSE COMMENT 'est-ce une villa ?',
    isappartement            BOOLEAN DEFAULT FALSE COMMENT 'est-ce un appartement ?',

    prix_terrains           DECIMAL(12,2) COMMENT 'prix terrain',
    prix_duplex          DECIMAL(12,2) COMMENT 'prix duplex',
    prix_villa           DECIMAL(12,2) COMMENT 'prix villa',
    prix_appartement           DECIMAL(12,2) COMMENT 'prix appartement',

    nb_logements        INT,
    pourcentage_apport  TINYINT NOT NULL COMMENT 'ex: 10',
    frais_souscription       INT NOT NULL COMMENT 'FCFA',
    est_actif           BOOLEAN DEFAULT TRUE,
    est_mutuelle        BOOLEAN DEFAULT FALSE COMMENT 'projet dédié mutuelle ?',
    mutuelle_id         INT NULL,
    cree_par            BIGINT NOT NULL,
    created_at          DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_projets_mutuelle FOREIGN KEY (mutuelle_id) REFERENCES mutuelles(id),
    CONSTRAINT fk_projets_cree_par FOREIGN KEY (cree_par) REFERENCES utilisateurs(id)
);




CREATE TABLE mutuelles (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    nom               VARCHAR(100) NOT NULL,
    code              VARCHAR(20) UNIQUE NOT NULL, genere automatiquement

    description       TEXT,
    valeur_du_bien    DECIMAL(12,2) NOT NULL COMMENT 'ex: 25000000 FCFA',
    taux_reduction    DECIMAL(5,2) NOT NULL COMMENT 'ex: 5.00',
    apport_initial    DECIMAL(12,2) NOT NULL COMMENT 'ex: 5000000 FCFA',
    est_active        BOOLEAN DEFAULT TRUE,
   projet_associe    INT NULL COMMENT 'projet lié à la mutuelle',

    cree_par          BIGINT NOT NULL,
    created_at        DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at        DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_mutuelles_cree_par FOREIGN KEY (cree_par) REFERENCES utilisateurs(id)
);




-- =========================================================
-- 2. PROJETS
-- =========================================================

-- =========================================================
-- 3. CLIENTS
-- =========================================================
CREATE TABLE clients (
    id             BIGINT AUTO_INCREMENT PRIMARY KEY,
    nom    VARCHAR(150) NOT NULL,
    prenom    VARCHAR(150) NOT NULL,
    telephone      VARCHAR(30),
    email          VARCHAR(255),
    date_naissance DATE,
    nationalite    VARCHAR(50),
    ayant_droit    VARCHAR(150),
    nombre_enfants TINYINT NULL COMMENT 'nombre d\'enfants',
    situation_familiale ENUM('Célibataire','Divorcé','Marié(e)','En couple') NULL COMMENT 'situation familiale',


    adresse        VARCHAR(255),
    categorie      ENUM('Individuel','Diaspora','Association','Mutuelle','Banque') NOT NULL,
    nature_piece_identite ENUM('Passeport','CNI','carte consulat','Permis de conduire','Carte d\'identité') NULL COMMENT 'nécessaire si catégorie est Individuel ou Diaspora',
    numero_piece_identite VARCHAR(50) NULL COMMENT 'numéro de la pièce d\'identité',
    created_at     DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_clients_mutuelle FOREIGN KEY (mutuelle_id) REFERENCES mutuelles(id)
);

-- =========================================================
-- 4. SOUSCRIPTIONS (dossiers)
-- =========================================================
CREATE TABLE souscriptions (
    id                  BIGINT AUTO_INCREMENT PRIMARY KEY,
    numero              VARCHAR(20) UNIQUE NOT NULL COMMENT 'SDB-2025-XXXXXX',
    client_id           BIGINT NOT NULL,
    projet_id           INT NOT NULL,
    type_logement       VARCHAR(50) NOT NULL COMMENT 'Villa basse, Duplex...',
    nb_pieces           TINYINT,
    prix_logement       DECIMAL(12,2) NOT NULL,
    apport_initial      DECIMAL(12,2) NOT NULL,
    frais_dossier       DECIMAL(10,2) NOT NULL,
    reste_a_payer       DECIMAL(12,2) NOT NULL,
    statut              ENUM('NOUVEAU','FRAIS_OK','APPORT_OK','RESERVE','SOLD','ATTRIBUE','CLOTURE') DEFAULT 'NOUVEAU',
    operateur_id        BIGINT NOT NULL COMMENT 'saisie opérateur',
    responsable_commercial_id BIGINT NULL,
    soumis_at           DATETIME NULL,
    cree_at             DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_souscriptions_client FOREIGN KEY (client_id) REFERENCES clients(id),
    CONSTRAINT fk_souscriptions_projet FOREIGN KEY (projet_id) REFERENCES projets(id),
    CONSTRAINT fk_souscriptions_operateur FOREIGN KEY (operateur_id) REFERENCES utilisateurs(id),
    CONSTRAINT fk_souscriptions_resp_com FOREIGN KEY (responsable_commercial_id) REFERENCES utilisateurs(id)


   
    date_debut_contrat DATE NULL COMMENT 'date de début de contrat',
    date_fin_contrat DATE NULL COMMENT 'date de fin de contrat',
    mode_paiement ENUM('ESPECES','VIREMENT','MOBILE_MONEY','TEMPERAMENT','CREDIT_BANCAIRE','CHEQUE','prelevement','transfer') NULL COMMENT 'mode de paiement préféré',
   
   
);
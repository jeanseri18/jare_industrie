-- =========================================================
--  JARES INDUSTRIES – MODÈLE DE BASE DE DONNÉES (FR)
-- =========================================================
-- Exécutez dans l’ordre (foreign keys en dernier)
-- =========================================================

-- 1. ROLES UTILISATEURS
CREATE TABLE roles (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    libelle       VARCHAR(50) NOT NULL COMMENT 'DG, Admin technique, DirCo, Comptabilité, Opérateur, Client',
    niveau        TINYINT NOT NULL COMMENT '1=DG, 2=Admin tech, 3=DirCo, 4=Compta, 5=Opérateur, 6=Client'
);

-- 2. UTILISATEURS
CREATE TABLE utilisateurs (
    id                BIGINT AUTO_INCREMENT PRIMARY KEY,
    nom               VARCHAR(100) NOT NULL,
    email             VARCHAR(255) NOT NULL UNIQUE,
    telephone         VARCHAR(30),
    mot_de_passe      VARCHAR(255) NOT NULL,
    role_id           INT NOT NULL,
    est_actif         BOOLEAN DEFAULT TRUE,
    derniere_connexion_at DATETIME NULL,
    cree_par          BIGINT NULL,
    created_at        DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at        DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_utilisateurs_role FOREIGN KEY (role_id) REFERENCES roles(id),
    CONSTRAINT fk_utilisateurs_cree_par FOREIGN KEY (cree_par) REFERENCES utilisateurs(id)
);

-- 3. MUTUELLES
CREATE TABLE mutuelles (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    nom           VARCHAR(100) NOT NULL,
    code          VARCHAR(20) UNIQUE NOT NULL,
    description   TEXT,
    type_remise   ENUM('P','M') NOT NULL COMMENT 'P=pourcentage, M=montant fixe',
    valeur_remise DECIMAL(10,2) NOT NULL,
    est_active    BOOLEAN DEFAULT TRUE,
    cree_par      BIGINT NOT NULL,
    created_at    DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_mutuelles_cree_par FOREIGN KEY (cree_par) REFERENCES utilisateurs(id)
);

-- 4. PROJETS
CREATE TABLE projets (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    nom               VARCHAR(150) NOT NULL,
    localisation      VARCHAR(255),
    nb_logements      INT,
    pourcentage_apport TINYINT NOT NULL COMMENT 'ex : 10%',
    frais_dossier     INT NOT NULL COMMENT 'en FCFA',
    est_actif         BOOLEAN DEFAULT TRUE,
    est_mutuelle      BOOLEAN DEFAULT FALSE COMMENT 'projet dédié mutuelle ?',
    mutuelle_id       INT NULL,
    cree_par          BIGINT NOT NULL,
    created_at        DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at        DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_projets_mutuelle FOREIGN KEY (mutuelle_id) REFERENCES mutuelles(id),
    CONSTRAINT fk_projets_cree_par FOREIGN KEY (cree_par) REFERENCES utilisateurs(id)
);

-- 5. TYPES_LOGEMENT
CREATE TABLE types_logement (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    libelle     VARCHAR(50) NOT NULL COMMENT 'Villa basse, Duplex, Appartement...'
);

-- 6. PRIX_PAR_TYPE (prix selon projet + type)
CREATE TABLE prix_par_type (
    projet_id        INT NOT NULL,
    type_logement_id INT NOT NULL,
    prix             DECIMAL(12,2) NOT NULL,
    PRIMARY KEY (projet_id, type_logement_id),
    CONSTRAINT fk_ppt_projet FOREIGN KEY (projet_id) REFERENCES projets(id) ON DELETE CASCADE,
    CONSTRAINT fk_ppt_type FOREIGN KEY (type_logement_id) REFERENCES types_logement(id)
);

-- 7. CLIENTS
CREATE TABLE clients (
    id              BIGINT AUTO_INCREMENT PRIMARY KEY,
    nom_complet     VARCHAR(150) NOT NULL,
    telephone       VARCHAR(30),
    email           VARCHAR(255),
    date_naissance  DATE,
    adresse         VARCHAR(255),
    categorie       ENUM('Individuel','Diaspora','Association','Mutuelle','Banque') NOT NULL,
    mutuelle_id     INT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_clients_mutuelle FOREIGN KEY (mutuelle_id) REFERENCES mutuelles(id)
);

-- 8. DOSSIERS (souscription)
CREATE TABLE dossiers (
    id               BIGINT AUTO_INCREMENT PRIMARY KEY,
    numero           VARCHAR(20) UNIQUE NOT NULL COMMENT 'SDB-2025-000123',
    client_id        BIGINT NOT NULL,
    projet_id        INT NOT NULL,
    type_logement_id INT NOT NULL,
    nb_pieces        TINYINT,
    prix_logement    DECIMAL(12,2) NOT NULL,
    apport_initial   DECIMAL(12,2) NOT NULL,
    frais_dossier    DECIMAL(10,2) NOT NULL,
    reste_a_payer    DECIMAL(12,2) NOT NULL,
    statut           ENUM('NOUVEAU','FRAIS_OK','APPORT_OK','RESERVE','SOLD','ATTRIBUE','CLOTURE') DEFAULT 'NOUVEAU',
    operateur_id     BIGINT NOT NULL COMMENT 'qui a saisi',
    responsable_commercial_id BIGINT NULL,
    cree_at          DATETIME DEFAULT CURRENT_TIMESTAMP,
    soumis_at        DATETIME NULL,
    CONSTRAINT fk_dossiers_client FOREIGN KEY (client_id) REFERENCES clients(id),
    CONSTRAINT fk_dossiers_projet FOREIGN KEY (projet_id) REFERENCES projets(id),
    CONSTRAINT fk_dossiers_type FOREIGN KEY (type_logement_id) REFERENCES types_logement(id),
    CONSTRAINT fk_dossiers_operateur FOREIGN KEY (operateur_id) REFERENCES utilisateurs(id),
    CONSTRAINT fk_dossiers_resp_com FOREIGN KEY (responsable_commercial_id) REFERENCES utilisateurs(id)
);

-- 9. DOCUMENTS_CLIENTS (PDF uploadés)
CREATE TABLE documents_clients (
    id          BIGINT AUTO_INCREMENT PRIMARY KEY,
    dossier_id  BIGINT NOT NULL,
    type_doc    ENUM('CNI','PASSEPORT','JUSTIF_MUTUELLE','JUSTIF_BANQUE','AUTRE') NOT NULL,
    fichier     VARCHAR(255) NOT NULL COMMENT 'chemin stockage',
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_docs_dossier FOREIGN KEY (dossier_id) REFERENCES dossiers(id) ON DELETE CASCADE
);

-- 10. PAIEMENTS
CREATE TABLE paiements (
    id            BIGINT AUTO_INCREMENT PRIMARY KEY,
    dossier_id    BIGINT NOT NULL,
    type          ENUM('FRAIS_DOSSIER','APPORT','PROJET') NOT NULL,
    montant       DECIMAL(10,2) NOT NULL,
    mode          ENUM('ESPECES','VIREMENT','MOBILE_MONEY','TEMPERAMENT','CREDIT_BANCAIRE') NOT NULL,
    reference     VARCHAR(100),
    date_paiement DATE NOT NULL,
    comptable_id  BIGINT NOT NULL,
    valide_at     DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_paiements_dossier FOREIGN KEY (dossier_id) REFERENCES dossiers(id),
    CONSTRAINT fk_paiements_comptable FOREIGN KEY (comptable_id) REFERENCES utilisateurs(id)
);

-- 11. ATTRIBUTIONS (DG)
CREATE TABLE attributions (
    id           BIGINT AUTO_INCREMENT PRIMARY KEY,
    dossier_id   BIGINT NOT NULL,
    lot          VARCHAR(20) NOT NULL,
    ilot         VARCHAR(20),
    numero_villa VARCHAR(20),
    date_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    dg_id        BIGINT NOT NULL,
    CONSTRAINT fk_attributions_dossier FOREIGN KEY (dossier_id) REFERENCES dossiers(id),
    CONSTRAINT fk_attributions_dg FOREIGN KEY (dg_id) REFERENCES utilisateurs(id)
);

-- 12. DOCUMENTS_OFFICIELS (PDF générés)
CREATE TABLE documents_officiels (
    id           BIGINT AUTO_INCREMENT PRIMARY KEY,
    dossier_id   BIGINT NOT NULL,
    type         ENUM('ATTESTATION_RESERVATION','LETTRE_ATTRIBUTION') NOT NULL,
    fichier      VARCHAR(255) NOT NULL COMMENT 'chemin PDF',
    genere_par   BIGINT NOT NULL,
    genere_at    DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_docs_off_dossier FOREIGN KEY (dossier_id) REFERENCES dossiers(id),
    CONSTRAINT fk_docs_off_user FOREIGN KEY (genere_par) REFERENCES utilisateurs(id)
);

-- 13. AUDIT_LOG (toutes actions sensibles)
CREATE TABLE audit_log (
    id          BIGINT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id BIGINT NOT NULL,
    action      VARCHAR(100) NOT NULL COMMENT 'CREATION_DOSSIER, VALIDATION_PAIEMENT, ATTRIBUTION...',
    entite      VARCHAR(50) COMMENT 'ex: DOSSIER',
    entite_id   BIGINT,
    avant       JSON NULL,
    apres       JSON NULL,
    ip          VARCHAR(45),
    user_agent  VARCHAR(255),
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_audit_user FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
);

-- =========================================================
-- INDEX POUR PERF
-- =========================================================
CREATE INDEX idx_dossiers_statut ON dossiers(statut);
CREATE INDEX idx_paiements_dossier ON paiements(dossier_id);
CREATE INDEX idx_audit_entite ON audit_log(entite, entite_id);
CREATE DATABASE IF NOT EXISTS bngrc_db;
USE bngrc_db;

CREATE TABLE bngrc_villes (
    id_ville INT AUTO_INCREMENT PRIMARY KEY,
    nom_ville VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE bngrc_categories (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE bngrc_besoins (
    id_besoin INT AUTO_INCREMENT PRIMARY KEY,
    id_ville INT NOT NULL,
    id_categorie INT NOT NULL,
    article VARCHAR(100) NOT NULL, 
    quantite_initiale DECIMAL(15,2) NOT NULL,
    quantite_restante DECIMAL(15,2) NOT NULL, 
    prix_unitaire DECIMAL(15,2) NOT NULL, 
    date_saisie TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ville) REFERENCES bngrc_villes(id_ville),
    FOREIGN KEY (id_categorie) REFERENCES bngrc_categories(id_categorie)
) ENGINE=InnoDB;


CREATE TABLE bngrc_dons (
    id_don INT AUTO_INCREMENT PRIMARY KEY,
    id_categorie INT NOT NULL,
    article VARCHAR(100) NOT NULL,
    quantite_donnee DECIMAL(15,2) NOT NULL,
    quantite_disponible DECIMAL(15,2) NOT NULL, 
    date_don TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categorie) REFERENCES bngrc_categories(id_categorie)
) ENGINE=InnoDB;

CREATE TABLE bngrc_dispatch (
    id_dispatch INT AUTO_INCREMENT PRIMARY KEY,
    id_besoin INT NOT NULL,
    id_don INT NOT NULL,
    quantite_attribuee DECIMAL(15,2) NOT NULL,
    date_dispatch TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_besoin) REFERENCES bngrc_besoins(id_besoin),
    FOREIGN KEY (id_don) REFERENCES bngrc_dons(id_don)
) ENGINE=InnoDB;

INSERT INTO bngrc_categories (nom_categorie) VALUES ('Nature'), ('Matériaux'), ('Argent');

INSERT INTO bngrc_villes (nom_ville) VALUES ('Antananarivo'), ('Tamatave'), ('Fianarantsoa'), ('Tulear');

ALTER TABLE bngrc_besoins ADD COLUMN unite VARCHAR(20) NOT NULL AFTER quantite_restante;
ALTER TABLE bngrc_dons ADD COLUMN unite VARCHAR(20) NOT NULL AFTER quantite_disponible;


###V3

-- Ajouter le type de distribution dans la table dons
ALTER TABLE bngrc_dons ADD COLUMN type_distribution VARCHAR(20) DEFAULT 'prioritaire';

-- Pour le bouton réinitialiser, on devra vider ces tables dans cet ordre :
-- DELETE FROM bngrc_achats;
-- DELETE FROM bngrc_dons;
-- UPDATE bngrc_besoins SET quantite_restante = quantite_initiale;


ALTER TABLE bngrc_dispatch ADD UNIQUE KEY unique_distribution (id_besoin, id_don);


-- Désactiver temporairement les contraintes
SET FOREIGN_KEY_CHECKS = 0;

-- Vider les tables dans l'ordre
TRUNCATE TABLE bngrc_dispatch;
TRUNCATE TABLE bngrc_dons;

-- AJOUTER LA SÉCURITÉ ANTI-DOUBLON (C'est le moment critique)
ALTER TABLE bngrc_dispatch ADD UNIQUE KEY unique_distribution (id_besoin, id_don);

-- Réactiver les contraintes
SET FOREIGN_KEY_CHECKS = 1;

-- Remettre les besoins à neuf
UPDATE bngrc_besoins SET quantite_restante = quantite_initiale;


SET FOREIGN_KEY_CHECKS = 0; TRUNCATE TABLE bngrc_dispatch; TRUNCATE TABLE bngrc_dons; SET FOREIGN_KEY_CHECKS = 1;
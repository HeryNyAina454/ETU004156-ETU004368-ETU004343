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



###########***********#############

ALTER TABLE bngrc_besoins AUTO_INCREMENT = 1;


-- Insertion ny donnees farany 
INSERT INTO bngrc_besoins (id_ville, id_categorie, article, quantite_initiale, quantite_restante, unite, prix_unitaire, date_saisie) VALUES
-- Ordre 1 à 5
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Tamatave'), 2, 'Bâche', 200, 200, 'pièce', 15000, '2026-02-15 08:01:00'),
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Nosy Be'), 2, 'Tôle', 40, 40, 'pièce', 25000, '2026-02-15 08:02:00'),
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Mananjary'), 3, 'Argent', 6000000, 6000000, 'MGA', 1, '2026-02-15 08:03:00'),
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Tamatave'), 1, 'Eau (L)', 1500, 1500, 'litre', 1000, '2026-02-15 08:04:00'),
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Nosy Be'), 1, 'Riz (kg)', 300, 300, 'kg', 3000, '2026-02-15 08:05:00'),

-- Ordre 6 à 10
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Mananjary'), 2, 'Tôle', 80, 80, 'pièce', 25000, '2026-02-15 08:06:00'),
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Nosy Be'), 3, 'Argent', 4000000, 4000000, 'MGA', 1, '2026-02-15 08:07:00'),
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Farafangana'), 2, 'Bâche', 150, 150, 'pièce', 15000, '2026-02-16 08:08:00'),
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Mananjary'), 1, 'Riz (kg)', 500, 500, 'kg', 3000, '2026-02-15 08:09:00'),
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Farafangana'), 3, 'Argent', 8000000, 8000000, 'MGA', 1, '2026-02-16 08:10:00'),

-- Ordre 11 à 15
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Morondava'), 1, 'Riz (kg)', 700, 700, 'kg', 3000, '2026-02-16 08:11:00'),
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Tamatave'), 3, 'Argent', 12000000, 12000000, 'MGA', 1, '2026-02-16 08:12:00'),
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Morondava'), 3, 'Argent', 10000000, 10000000, 'MGA', 1, '2026-02-16 08:13:00'),
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Farafangana'), 1, 'Eau (L)', 1000, 1000, 'litre', 1000, '2026-02-15 08:14:00'),
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Morondava'), 2, 'Bâche', 180, 180, 'pièce', 15000, '2026-02-16 08:15:00'),

-- Ordre 16 à 20
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Tamatave'), 2, 'groupe', 3, 3, 'pièce', 6750000, '2026-02-15 08:16:00'),
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Tamatave'), 1, 'Riz (kg)', 800, 800, 'kg', 3000, '2026-02-16 08:17:00'),
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Nosy Be'), 1, 'Haricots', 200, 200, 'kg', 4000, '2026-02-16 08:18:00'),
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Mananjary'), 2, 'Clous (kg)', 60, 60, 'kg', 8000, '2026-02-16 08:19:00'),
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Morondava'), 1, 'Eau (L)', 1200, 1200, 'litre', 1000, '2026-02-15 08:20:00'),

-- Ordre 21 à 26
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Farafangana'), 1, 'Riz (kg)', 600, 600, 'kg', 3000, '2026-02-16 08:21:00'),
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Morondava'), 2, 'Bois', 150, 150, 'pièce', 10000, '2026-02-15 08:22:00'),
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Tamatave'), 2, 'Tôle', 120, 120, 'pièce', 25000, '2026-02-16 08:23:00'),
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Nosy Be'), 2, 'Clous (kg)', 30, 30, 'kg', 8000, '2026-02-16 08:24:00'),
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Mananjary'), 1, 'Huile (L)', 120, 120, 'litre', 6000, '2026-02-16 08:25:00'),
((SELECT id_ville FROM bngrc_villes WHERE nom_ville='Farafangana'), 2, 'Bois', 100, 100, 'pièce', 10000, '2026-02-15 08:26:00');



-- Simplification des libellés (pas d'accents, pas de parenthèses)
UPDATE bngrc_besoins SET article = 'riz' WHERE article = 'Riz (kg)';
UPDATE bngrc_besoins SET article = 'eau' WHERE article = 'Eau (L)';
UPDATE bngrc_besoins SET article = 'tole' WHERE article = 'Tôle';
UPDATE bngrc_besoins SET article = 'bache' WHERE article = 'Bâche';
UPDATE bngrc_besoins SET article = 'bois' WHERE article = 'Bois';
UPDATE bngrc_besoins SET article = 'groupe' WHERE article = 'groupe';
UPDATE bngrc_besoins SET article = 'clou' WHERE article LIKE 'Clous%';
UPDATE bngrc_besoins SET article = 'haricot' WHERE article = 'Haricots';
UPDATE bngrc_besoins SET article = 'huile' WHERE article LIKE 'Huile%';
UPDATE bngrc_besoins SET article = 'argent' WHERE article = 'Argent';



-- Nettoyage avant insertion des nouveaux dons
DELETE FROM bngrc_dispatch;
DELETE FROM bngrc_dons;

-- Insertion des dons de la capture d'écran
INSERT INTO bngrc_dons (id_categorie, article, quantite_donnee, quantite_disponible, unite, date_don, type_distribution) VALUES
(3, 'argent', 5000000, 5000000, 'MGA', '2026-02-16 10:00:00', 'prioritaire'),
(3, 'argent', 3000000, 3000000, 'MGA', '2026-02-16 11:00:00', 'prioritaire'),
(3, 'argent', 4000000, 4000000, 'MGA', '2026-02-17 09:00:00', 'prioritaire'),
(3, 'argent', 1500000, 1500000, 'MGA', '2026-02-17 10:00:00', 'prioritaire'),
(3, 'argent', 6000000, 6000000, 'MGA', '2026-02-17 11:00:00', 'prioritaire'),
(1, 'riz', 400, 400, 'kg', '2026-02-16 14:00:00', 'prioritaire'),
(1, 'eau', 600, 600, 'litre', '2026-02-16 15:00:00', 'prioritaire'),
(2, 'tole', 50, 50, 'pièce', '2026-02-17 13:00:00', 'prioritaire'),
(2, 'bache', 70, 70, 'pièce', '2026-02-17 14:00:00', 'prioritaire'),
(1, 'haricot', 100, 100, 'kg', '2026-02-17 15:00:00', 'prioritaire'),
(1, 'riz', 2000, 2000, 'kg', '2026-02-18 08:00:00', 'prioritaire'),
(2, 'tole', 300, 300, 'pièce', '2026-02-18 09:00:00', 'prioritaire'),
(1, 'eau', 5000, 5000, 'litre', '2026-02-18 10:00:00', 'prioritaire'),
(3, 'argent', 20000000, 20000000, 'MGA', '2026-02-19 08:00:00', 'prioritaire'),
(2, 'bache', 500, 500, 'pièce', '2026-02-19 09:00:00', 'prioritaire'),
(1, 'haricot', 88, 88, 'kg', '2026-02-17 16:00:00', 'prioritaire');
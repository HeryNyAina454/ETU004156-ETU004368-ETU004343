
CREATE TABLE IF NOT EXISTS bngrc_params (
    cle VARCHAR(50) PRIMARY KEY,
    valeur DECIMAL(10,2) NOT NULL
);

INSERT IGNORE INTO bngrc_params (cle, valeur) VALUES ('frais_achat', 10.00);


CREATE TABLE IF NOT EXISTS bngrc_achats (
    id_achat INT AUTO_INCREMENT PRIMARY KEY,
    id_besoin INT,
    quantite_achetee DECIMAL(10,2),
    montant_total_avec_frais DECIMAL(15,2),
    date_achat DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_besoin) REFERENCES bngrc_besoins(id_besoin)
);
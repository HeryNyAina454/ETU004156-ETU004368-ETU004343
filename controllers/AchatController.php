<?php
require_once '../models/Achat.php';
require_once '../models/Besoin.php';

class AchatController {
    public function liste() {
        $db = (new Database())->getConnection();
        
        
        $stmt = $db->query("SELECT valeur FROM bngrc_params WHERE cle = 'frais_achat'");
        $frais_achat = $stmt->fetchColumn();

        $ville_filtre = $_POST['ville_filtre'] ?? '';
        
        
        $sql = "SELECT b.*, v.nom_ville as ville 
                FROM bngrc_besoins b
                JOIN bngrc_villes v ON b.id_ville = v.id_ville
                WHERE b.quantite_restante > 0";
        
        if ($ville_filtre) {
            $sql .= " AND v.nom_ville LIKE :v";
        }
        
        $stmt = $db->prepare($sql);
        if ($ville_filtre) {
            $stmt->execute([':v' => "%$ville_filtre%"]);
        } else {
            $stmt->execute();
        }
        
        $besoins = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        include '../views/liste_besoins_achats.php';
    }

    public function simuler() {
        $db = (new Database())->getConnection();
        
        $id_besoin = $_GET['id_besoin'] ?? null;
        $qte_simulee = $_GET['qte'] ?? 0;

        if (!$id_besoin) {
            header("Location: index.php?action=liste-besoins-achats");
            exit;
        }

       
        $stmt = $db->prepare("SELECT * FROM bngrc_besoins WHERE id_besoin = ?");
        $stmt->execute([$id_besoin]);
        $besoin = $stmt->fetch(PDO::FETCH_ASSOC);

        
        $stmtFrais = $db->query("SELECT valeur FROM bngrc_params WHERE cle = 'frais_achat'");
        $frais_taux = $stmtFrais->fetchColumn();

        $article = $besoin['article'];
        $unite = $besoin['unite'];
        $prix_u = $besoin['prix_unitaire'];
        $montant_base = $qte_simulee * $prix_u;
        $montant_frais = $montant_base * ($frais_taux / 100);
        $total_ttc = $montant_base + $montant_frais;

        $reste_actuel = $besoin['quantite_restante'];
        $reste_apres = $reste_actuel - $qte_simulee;

        $stmtArgent = $db->prepare("SELECT SUM(quantite_disponible) FROM bngrc_dons WHERE LOWER(TRIM(article)) = 'argent'");
        $stmtArgent->execute();
        $argent_actuel = $stmtArgent->fetchColumn() ?: 0;
        $argent_apres = $argent_actuel - $total_ttc;

        include '../views/simulation_achat.php';
    }

    public function valider() {
        $db = (new Database())->getConnection();
        
        $id_besoin = $_REQUEST['id_besoin'] ?? null;
        $qte_a_acheter = $_REQUEST['qte'] ?? 0;

        if (!$id_besoin || $qte_a_acheter <= 0) {
            die("Données d'achat invalides.");
        }

        $stmt = $db->prepare("SELECT article FROM bngrc_besoins WHERE id_besoin = ?");
        $stmt->execute([$id_besoin]);
        $besoin = $stmt->fetch(PDO::FETCH_ASSOC);

       
        $stmt = $db->prepare("SELECT SUM(quantite_disponible) FROM bngrc_dons WHERE LOWER(TRIM(article)) = LOWER(TRIM(?))");
        $stmt->execute([$besoin['article']]);
        $stock_don = $stmt->fetchColumn();

        if ($stock_don > 0) {
            die("Erreur : Impossible d'acheter cet article car il en reste encore dans les dons reçus.");
        }

        
        $achatModel = new Achat($db);
        $resultat = $achatModel->effectuerAchat($id_besoin, $qte_a_acheter);

        if ($resultat === true) {
            header("Location: index.php?action=recapitulatif");
        } else {
            die("Erreur lors de l'achat : " . $resultat);
        }
    }
}
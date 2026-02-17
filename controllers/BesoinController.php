<?php
require_once '../models/Besoin.php';
require_once '../models/Ville.php';

class BesoinController {
    public function form() {
        $db = (new Database())->getConnection();
        $villeModel = new Ville($db);
        $villes = $villeModel->getAll();
        
        $title = "Saisie des Besoins";
        require_once '../views/saisie_besoin.php';
    }

    public function save() {
        $db = (new Database())->getConnection();
        $besoinModel = new Besoin($db);

        $besoinModel->create(
            $_POST['id_ville'],
            $_POST['id_categorie'],
            $_POST['article'],
            $_POST['quantite'],
            $_POST['prix_unitaire']
        );

        header("Location: index.php?action=dashboard");
    }

    public function delete() {
        if (!isset($_GET['id'])) {
            header("Location: index.php?action=dashboard");
            exit();
        }

        $id = $_GET['id'];
        $db = (new Database())->getConnection();

        try {
            $db->beginTransaction();
            
            // Supprimer les liens de distribution (Dons nature)
            $db->prepare("DELETE FROM bngrc_dispatch WHERE id_besoin = ?")->execute([$id]);
            
            // Supprimer les liens d'achats (Dons argent V2)
            $db->prepare("DELETE FROM bngrc_achats WHERE id_besoin = ?")->execute([$id]);
            
            // Supprimer le besoin lui-même
            $db->prepare("DELETE FROM bngrc_besoins WHERE id_besoin = ?")->execute([$id]);

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            // Optionnel: logger l'erreur $e->getMessage()
        }

        header("Location: index.php?action=dashboard");
        exit();
    }
}
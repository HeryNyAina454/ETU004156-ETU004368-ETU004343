<?php
require_once '../models/Don.php';

class DonController {
    public function form() {
        $title = "Saisie des Dons";
        require_once '../views/saisie_don.php';
    }

    public function save() {
        $db = (new Database())->getConnection();
        $donModel = new Don($db);

        $id_categorie      = $_POST['id_categorie'];
        $article           = $_POST['article'];
        $quantite          = $_POST['quantite'];
        $unite             = $_POST['unite'];
        $type_distribution = $_POST['type_distribution'];

        $donModel->saveAndDispatch(
            $id_categorie,
            $article,
            $quantite,
            $unite,
            $type_distribution
        );

        header("Location: index.php?action=dashboard");
        exit();
    }

    public function reinitialiser() {
        $db = (new Database())->getConnection();
        
        try {
            $db->exec("SET FOREIGN_KEY_CHECKS = 0");

            $db->exec("TRUNCATE TABLE bngrc_dispatch");
            $db->exec("TRUNCATE TABLE bngrc_achats");
            $db->exec("TRUNCATE TABLE bngrc_dons");
            $db->exec("UPDATE bngrc_besoins SET quantite_restante = quantite_initiale");

            $db->exec("SET FOREIGN_KEY_CHECKS = 1");

            header("Location: index.php?action=dashboard");
            exit();
        } catch (Exception $e) {
            die("Erreur reset : " . $e->getMessage());
        }
    }
}
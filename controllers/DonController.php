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

        // On récupère les 4 données nécessaires du formulaire
        $id_categorie = $_POST['id_categorie'];
        $article      = $_POST['article'];
        $quantite     = $_POST['quantite'];
        $unite        = $_POST['unite']; // <--- Cet argument manquait !

        // On appelle la fonction avec les 4 paramètres attendus par le modèle
        $donModel->saveAndDispatch(
            $id_categorie,
            $article,
            $quantite,
            $unite
        );

        header("Location: index.php?action=dashboard");
    }
}
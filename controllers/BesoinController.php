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
}
<?php
class DashboardController {
    public function index() {
        $db = (new Database())->getConnection();
        
        $query = "SELECT b.id_besoin, v.nom_ville, b.article, b.quantite_initiale, b.quantite_restante, b.prix_unitaire,
                  (SELECT SUM(quantite_attribuee) FROM bngrc_dispatch WHERE id_besoin = b.id_besoin) as total_recu
                  FROM bngrc_villes v
                  LEFT JOIN bngrc_besoins b ON v.id_ville = b.id_ville
                  ORDER BY v.nom_ville ASC";
        
        $stmt = $db->prepare($query);
        $stmt->execute();
        $donnees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $title = "Tableau de Bord BNGRC";
        require_once '../views/dashboard.php';
    }

    public function recap() {
        include '../views/recapitulation.php';
    }
}
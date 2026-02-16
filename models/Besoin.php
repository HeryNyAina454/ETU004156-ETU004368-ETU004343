<?php
class Besoin {
    private $conn;
    private $table = "bngrc_besoins";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($id_ville, $id_categorie, $article, $quantite, $prix_unitaire) {
        $query = "INSERT INTO " . $this->table . " 
                  (id_ville, id_categorie, article, quantite_initiale, quantite_restante, prix_unitaire) 
                  VALUES (:id_v, :id_c, :art, :q_i, :q_r, :pu)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':id_v' => $id_ville,
            ':id_c' => $id_categorie,
            ':art'  => $article,
            ':q_i'  => $quantite,
            ':q_r'  => $quantite,
            ':pu'   => $prix_unitaire
        ]);
        return $this->conn->lastInsertId();
    }

    public function getBesoinsOuvertsParCategorie($id_categorie) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE id_categorie = :id_c AND quantite_restante > 0 
                  ORDER BY date_saisie ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id_c' => $id_categorie]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateRestant($id_besoin, $nouveau_restant) {
        $query = "UPDATE " . $this->table . " SET quantite_restante = :q WHERE id_besoin = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':q' => $nouveau_restant, ':id' => $id_besoin]);
    }
}
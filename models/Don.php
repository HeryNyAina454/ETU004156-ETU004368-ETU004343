<?php
class Don {
    private $conn;
    private $table = "bngrc_dons";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function saveAndDispatch($id_categorie, $article, $quantite, $unite) {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO " . $this->table . " (id_categorie, article, quantite_donnee, quantite_disponible, unite) 
                      VALUES (:id_c, :art, :q_d, :q_disp, :u)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':id_c'    => $id_categorie,
                ':art'     => trim($article),
                ':q_d'     => $quantite,
                ':q_disp'  => $quantite,
                ':u'       => trim($unite)
            ]);
            $id_don = $this->conn->lastInsertId();

            $this->dispatch($id_don, trim($article), $quantite);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    private function dispatch($id_don, $article, $quantite_dispo) {
        $query = "SELECT id_besoin, quantite_restante FROM bngrc_besoins 
                  WHERE LOWER(article) LIKE LOWER(:art) 
                  AND quantite_restante > 0 
                  ORDER BY id_besoin ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':art' => '%' . trim($article) . '%']);
        
        $besoins = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($besoins as $b) {
            if ($quantite_dispo <= 0) break;

            $prelevement = min($quantite_dispo, $b['quantite_restante']);
            
            $sqlDispatch = "INSERT INTO bngrc_dispatch (id_besoin, id_don, quantite_attribuee) 
                            VALUES (:id_b, :id_d, :q)";
            $this->conn->prepare($sqlDispatch)->execute([
                ':id_b' => $b['id_besoin'],
                ':id_d' => $id_don,
                ':q'    => $prelevement
            ]);

            $nouveauRestant = $b['quantite_restante'] - $prelevement;
            $sqlUpBesoin = "UPDATE bngrc_besoins SET quantite_restante = :r WHERE id_besoin = :id";
            $this->conn->prepare($sqlUpBesoin)->execute([
                ':r' => $nouveauRestant, 
                ':id' => $b['id_besoin']
            ]);

            $quantite_dispo -= $prelevement;
        }

        $sqlUpDon = "UPDATE " . $this->table . " SET quantite_disponible = :q WHERE id_don = :id";
        $this->conn->prepare($sqlUpDon)->execute([
            ':q' => $quantite_dispo, 
            ':id' => $id_don
        ]);
    }
}
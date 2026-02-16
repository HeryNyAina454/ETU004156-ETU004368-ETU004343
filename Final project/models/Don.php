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
                ':art'     => $article,
                ':q_d'     => $quantite,
                ':q_disp'  => $quantite,
                ':u'       => $unite
            ]);
            $id_don = $this->conn->lastInsertId();

            // On distribue aux besoins qui ont le MEME article et la MEME unité
            $this->dispatch($id_don, $article, $quantite, $unite);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    private function dispatch($id_don, $article, $quantite_dispo, $unite) {
        // Recherche des besoins non satisfaits pour cet article précis et cette unité
        $query = "SELECT id_besoin, quantite_restante FROM bngrc_besoins 
                  WHERE article = :art AND unite = :u AND quantite_restante > 0 
                  ORDER BY date_besoin ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':art' => $article, ':u' => $unite]);
        $besoins = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($besoins as $b) {
            if ($quantite_dispo <= 0) break;

            $prelevement = min($quantite_dispo, $b['quantite_restante']);
            
            // Enregistre la distribution
            $sqlDispatch = "INSERT INTO bngrc_dispatch (id_besoin, id_don, quantite_attribuee) 
                            VALUES (:id_b, :id_d, :q)";
            $stmtDisp = $this->conn->prepare($sqlDispatch);
            $stmtDisp->execute([
                ':id_b' => $b['id_besoin'],
                ':id_d' => $id_don,
                ':q'    => $prelevement
            ]);

            // Met à jour le besoin
            $nouveauRestant = $b['quantite_restante'] - $prelevement;
            $sqlUpBesoin = "UPDATE bngrc_besoins SET quantite_restante = :r WHERE id_besoin = :id";
            $stmtUp = $this->conn->prepare($sqlUpBesoin);
            $stmtUp->execute([':r' => $nouveauRestant, ':id' => $b['id_besoin']]);

            $quantite_dispo -= $prelevement;
        }

        // Met à jour ce qui reste du don (si personne n'en avait besoin)
        $queryUpdateDon = "UPDATE " . $this->table . " SET quantite_disponible = :q WHERE id_don = :id";
        $stmtUpdateDon = $this->conn->prepare($queryUpdateDon);
        $stmtUpdateDon->execute([':q' => $quantite_dispo, ':id' => $id_don]);
    }
}
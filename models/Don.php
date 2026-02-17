<?php
class Don {
    private $conn;
    private $table = "bngrc_dons";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function saveAndDispatch($id_categorie, $article, $quantite, $unite, $type_distribution) {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO " . $this->table . " (id_categorie, article, quantite_donnee, quantite_disponible, unite, type_distribution) 
                      VALUES (:id_c, :art, :q_d, :q_disp, :u, :t_d)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':id_c'   => $id_categorie,
                ':art'    => trim($article),
                ':q_d'    => $quantite,
                ':q_disp' => $quantite,
                ':u'      => trim($unite),
                ':t_d'    => $type_distribution
            ]);
            $id_don = $this->conn->lastInsertId();

            if ($id_categorie != 3) {
                $this->dispatch($id_don, trim($article), $quantite, $type_distribution);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    private function dispatch($id_don, $article, $quantite_dispo, $type_distribution) {
        $sql = "SELECT id_besoin, quantite_restante FROM bngrc_besoins 
                WHERE LOWER(article) LIKE LOWER(:art) AND quantite_restante > 0";
        
        if ($type_distribution == 'prioritaire') {
            $sql .= " ORDER BY id_besoin ASC";
        } elseif ($type_distribution == 'decroissant') {
            $sql .= " ORDER BY quantite_restante ASC";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':art' => '%' . trim($article) . '%']);
        $besoins = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($type_distribution == 'proportionnel' && !empty($besoins)) {
            $total_demande = 0;
            foreach ($besoins as $b) $total_demande += $b['quantite_restante'];

            foreach ($besoins as $b) {
                $part = floor(($b['quantite_restante'] / $total_demande) * $quantite_dispo);
                $prelevement = min($part, $b['quantite_restante']);
                
                if ($prelevement > 0) {
                    $this->appliquerPrelevement($b['id_besoin'], $id_don, $prelevement);
                    $quantite_dispo -= $prelevement;
                }
            }
        } else {
            foreach ($besoins as $b) {
                if ($quantite_dispo <= 0) break;
                $prelevement = min($quantite_dispo, $b['quantite_restante']);
                $this->appliquerPrelevement($b['id_besoin'], $id_don, $prelevement);
                $quantite_dispo -= $prelevement;
            }
        }

        $sqlUpDon = "UPDATE " . $this->table . " SET quantite_disponible = :q WHERE id_don = :id";
        $this->conn->prepare($sqlUpDon)->execute([':q' => $quantite_dispo, ':id' => $id_don]);
    }

    private function appliquerPrelevement($id_besoin, $id_don, $quantite) {
        $sqlDispatch = "INSERT INTO bngrc_dispatch (id_besoin, id_don, quantite_attribuee) VALUES (:id_b, :id_d, :q)";
        $this->conn->prepare($sqlDispatch)->execute([':id_b' => $id_besoin, ':id_d' => $id_don, ':q' => $quantite]);

        $sqlUpBesoin = "UPDATE bngrc_besoins SET quantite_restante = quantite_restante - :q WHERE id_besoin = :id";
        $this->conn->prepare($sqlUpBesoin)->execute([':q' => $quantite, ':id' => $id_besoin]);
    }
}
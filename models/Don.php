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
                $this->dispatch($id_don, trim($article), (float)$quantite, $type_distribution);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return false;
        }
    }

    private function dispatch($id_don, $article, $quantite_initiale_don, $type_distribution) {
        $quantite_dispo = (float)$quantite_initiale_don;
        
        $sql = "SELECT id_besoin, quantite_initiale, quantite_restante FROM bngrc_besoins 
                WHERE LOWER(article) = LOWER(:art) AND quantite_restante > 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':art' => trim($article)]);
        $besoins = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($besoins)) return;

        if ($type_distribution === 'proportionnel') {
            $total_demande = 0;
            foreach ($besoins as $b) { 
                $total_demande += (float)$b['quantite_initiale']; 
            }

            $repartition = [];
            $somme_attribuee = 0;

            foreach ($besoins as $b) {
                $valeur_exacte = ((float)$b['quantite_initiale'] * $quantite_initiale_don) / $total_demande;
                $part_entiere = floor(round($valeur_exacte, 8));
                
                $repartition[] = [
                    'id_besoin' => $b['id_besoin'],
                    'attribue'  => (float)$part_entiere,
                    'decimal'   => $valeur_exacte - $part_entiere,
                    'max'       => (float)$b['quantite_restante']
                ];
                $somme_attribuee += $part_entiere;
            }

            $reste = (int)round($quantite_initiale_don - $somme_attribuee);
            if ($reste > 0) {
                usort($repartition, function($a, $b) { 
                    return $b['decimal'] <=> $a['decimal']; 
                });

                foreach ($repartition as &$r) {
                    if ($reste <= 0) break;
                    if ($r['attribue'] < $r['max']) {
                        $r['attribue'] += 1;
                        $reste -= 1;
                    }
                }
            }

            foreach ($repartition as $r) {
                if ($r['attribue'] > 0) {
                    $this->appliquerPrelevement($r['id_besoin'], $id_don, $r['attribue']);
                    $quantite_dispo -= $r['attribue'];
                }
            }
        } else {
            foreach ($besoins as $b) {
                if ($quantite_dispo <= 0) break;
                $p = min($quantite_dispo, (float)$b['quantite_restante']);
                if ($p > 0) {
                    $this->appliquerPrelevement($b['id_besoin'], $id_don, $p);
                    $quantite_dispo -= $p;
                }
            }
        }

        $this->conn->prepare("UPDATE " . $this->table . " SET quantite_disponible = :q WHERE id_don = :id")
                   ->execute([':q' => max(0, $quantite_dispo), ':id' => $id_don]);
    }

    private function appliquerPrelevement($id_besoin, $id_don, $quantite) {
       
        $sql1 = "INSERT INTO bngrc_dispatch (id_besoin, id_don, quantite_attribuee) 
                 VALUES (:id_b, :id_d, :q) 
                 ON DUPLICATE KEY UPDATE quantite_attribuee = VALUES(quantite_attribuee)";
        $stmt1 = $this->conn->prepare($sql1);
        $stmt1->execute([':id_b' => $id_besoin, ':id_d' => $id_don, ':q' => $quantite]);

        $sql2 = "UPDATE bngrc_besoins SET quantite_restante = ROUND(quantite_restante - :q, 2) WHERE id_besoin = :id";
        $stmt2 = $this->conn->prepare($sql2);
        $stmt2->execute([':q' => $quantite, ':id' => $id_besoin]);
    }
}
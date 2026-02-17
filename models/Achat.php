<?php
class Achat {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function effectuerAchat($id_besoin, $qte_a_acheter) {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("SELECT article, prix_unitaire, quantite_restante FROM bngrc_besoins WHERE id_besoin = ?");
            $stmt->execute([$id_besoin]);
            $besoin = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmtFrais = $this->conn->query("SELECT valeur FROM bngrc_params WHERE cle = 'frais_achat'");
            $taux_frais = $stmtFrais->fetchColumn() / 100;

            $montant_base = $qte_a_acheter * $besoin['prix_unitaire'];
            $montant_total = $montant_base * (1 + $taux_frais);

            $stmtArgent = $this->conn->query("SELECT SUM(quantite_disponible) FROM bngrc_dons WHERE LOWER(article) = 'argent'");
            $argent_dispo = $stmtArgent->fetchColumn();

            if ($argent_dispo < $montant_total) {
                throw new Exception("Fonds insuffisants. Argent dispo: $argent_dispo Ar, Requis: $montant_total Ar");
            }
            $this->deduireArgent($montant_total);

           
            $nouveau_reste = $besoin['quantite_restante'] - $qte_a_acheter;
            $upBesoin = $this->conn->prepare("UPDATE bngrc_besoins SET quantite_restante = :r WHERE id_besoin = :id");
            $upBesoin->execute([':r' => $nouveau_reste, ':id' => $id_besoin]);

            $insAchat = $this->conn->prepare("INSERT INTO bngrc_achats (id_besoin, quantite_achetee, montant_total_avec_frais) VALUES (?, ?, ?)");
            $insAchat->execute([$id_besoin, $qte_a_acheter, $montant_total]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return $e->getMessage();
        }
    }

    private function deduireArgent($montant_a_retirer) {
        $stmt = $this->conn->query("SELECT id_don, quantite_disponible FROM bngrc_dons WHERE LOWER(article) = 'argent' AND quantite_disponible > 0 ORDER BY id_don ASC");
        $dons_argent = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($dons_argent as $don) {
            if ($montant_a_retirer <= 0) break;

            $prelevement = min($montant_a_retirer, $don['quantite_disponible']);
            $nouveau_dispo = $don['quantite_disponible'] - $prelevement;

            $up = $this->conn->prepare("UPDATE bngrc_dons SET quantite_disponible = ? WHERE id_don = ?");
            $up->execute([$nouveau_dispo, $don['id_don']]);

            $montant_a_retirer -= $prelevement;
        }
    }
}
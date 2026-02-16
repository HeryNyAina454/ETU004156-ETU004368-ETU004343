<?php
require_once '../../config/database.php';

try {
    $db = (new Database())->getConnection();

    // Calcul des montants financiers demandés pour la V2
    $query = "SELECT 
                IFNULL(SUM(quantite_initiale * prix_unitaire), 0) as total,
                IFNULL(SUM((quantite_initiale - quantite_restante) * prix_unitaire), 0) as satisfait,
                IFNULL(SUM(quantite_restante * prix_unitaire), 0) as restant
              FROM bngrc_besoins";

    $stmt = $db->query($query);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($data);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
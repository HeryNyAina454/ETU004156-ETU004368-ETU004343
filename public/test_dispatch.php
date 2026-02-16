<?php
require_once '../config/database.php';
require_once '../models/Besoin.php';
require_once '../models/Don.php';

$db = (new Database())->getConnection();
$db->exec("DELETE FROM bngrc_dispatch; DELETE FROM bngrc_dons; DELETE FROM bngrc_besoins;");

$besoinModel = new Besoin($db);
$donModel = new Don($db);


$besoinModel->create(1, 1, 'Riz', 100, 2500); 

$besoinModel->create(2, 1, 'Riz', 100, 2500); 

echo "Besoins créés : Tana (100kg), Tamatave (100kg).<br>";


echo "Arrivée d'un don de 150kg de Riz...<br>";
$donModel->saveAndDispatch(1, 'Riz', 150);

$query = "SELECT v.nom_ville, b.quantite_initiale, b.quantite_restante 
          FROM bngrc_besoins b 
          JOIN bngrc_villes v ON b.id_ville = v.id_ville";
$stmt = $db->query($query);
$resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h3>Résultat du Dispatch :</h3>";
foreach($resultats as $res) {
    echo "Ville: " . $res['nom_ville'] . " | Initial: " . $res['quantite_initiale'] . " | Reste: " . $res['quantite_restante'] . "<br>";
}
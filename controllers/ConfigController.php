<?php
class ConfigController {
    public function index() {
        $db = (new Database())->getConnection();
        
        $stmt = $db->query("SELECT valeur FROM bngrc_params WHERE cle = 'frais_achat'");
        $frais_actuel = $stmt->fetchColumn();
        
        $title = "Configuration des Frais";
        include '../views/config_frais.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = (new Database())->getConnection();
            $nouvel_achat = $_POST['frais_achat'];

            $stmt = $db->prepare("UPDATE bngrc_params SET valeur = :v WHERE cle = 'frais_achat'");
            $stmt->execute([':v' => $nouvel_achat]);

            header("Location: index.php?action=config-frais&status=success");
            exit;
        }
    }
}
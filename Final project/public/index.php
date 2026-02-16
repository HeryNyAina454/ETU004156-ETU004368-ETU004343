<?php
require_once '../config/database.php';

$action = $_GET['action'] ?? 'dashboard';

if ($action === 'dashboard') {
    require_once '../controllers/DashboardController.php';
    (new DashboardController())->index();
} elseif ($action === 'saisie-besoin') {
    require_once '../controllers/BesoinController.php';
    (new BesoinController())->form();
} elseif ($action === 'save-besoin') {
    require_once '../controllers/BesoinController.php';
    (new BesoinController())->save();
} elseif ($action === 'saisie-don') {
    require_once '../controllers/DonController.php';
    (new DonController())->form();
} elseif ($action === 'save-don') {
    require_once '../controllers/DonController.php';
    (new DonController())->save();
} else {
    header("Location: index.php?action=dashboard");
}
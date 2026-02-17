<?php
require_once '../config/database.php';

$action = $_GET['action'] ?? 'dashboard';

switch ($action) {
    case 'dashboard':
        require_once '../controllers/DashboardController.php';
        (new DashboardController())->index();
        break;

    case 'saisie-besoin':
        require_once '../controllers/BesoinController.php';
        (new BesoinController())->form();
        break;

    case 'save-besoin':
        require_once '../controllers/BesoinController.php';
        (new BesoinController())->save();
        break;

    case 'saisie-don':
        require_once '../controllers/DonController.php';
        (new DonController())->form();
        break;

    case 'get-articles':
        require_once '../controllers/DonController.php';
        (new DonController())->getArticles();
        break;

    case 'save-don':
        require_once '../controllers/DonController.php';
        (new DonController())->save();
        break;

    case 'liste-besoins-achats':
        require_once '../controllers/AchatController.php';
        (new AchatController())->liste();
        break;

    case 'simuler-achat':
        require_once '../controllers/AchatController.php';
        (new AchatController())->simuler();
        break;

    case 'valider-achat':
        require_once '../controllers/AchatController.php';
        (new AchatController())->valider();
        break;

    case 'config-frais':
        require_once '../controllers/ConfigController.php';
        (new ConfigController())->index();
        break;

    case 'save-config':
        require_once '../controllers/ConfigController.php';
        (new ConfigController())->save();
        break;

    case 'recapitulatif':
        require_once '../controllers/DashboardController.php';
        (new DashboardController())->recap();
        break;

    case 'delete-besoin':
        require_once '../controllers/BesoinController.php';
        (new BesoinController())->delete();
        break;

    case 'reinitialiser':
        require_once '../controllers/DonController.php';
        (new DonController())->reinitialiser();
        break;

    default:
        header("Location: index.php?action=dashboard");
        exit;
}
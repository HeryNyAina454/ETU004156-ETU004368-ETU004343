<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - BNGRC</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        header {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 10px 0;
        }
        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        nav ul {
            display: flex;
            list-style: none;
            gap: 20px;
            margin: 0;
            padding: 0;
        }
        nav ul li {
            position: relative;
        }
        nav ul li a {
            text-decoration: none;
            color: #444;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 8px 0;
            transition: color 0.3s ease;
        }
        nav ul li a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            bottom: -5px;
            left: 0;
            background-color: #007bff;
            transition: width 0.3s ease;
            border-radius: 2px;
        }
        nav ul li a:hover {
            color: #007bff;
        }
        nav ul li a:hover::after {
            width: 100%;
        }
        nav ul li.active a {
            color: #007bff;
        }
        nav ul li.active a::after {
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo-container">
                <a href="index.php?action=dashboard">
                    <img src="images/bngrc.png" alt="Logo BNGRC" style="height: 50px; width: auto; vertical-align: middle;">
                </a>
            </div>
            <ul>
                <?php $act = $_GET['action'] ?? 'dashboard'; ?>
                <li class="<?php echo ($act == 'dashboard') ? 'active' : ''; ?>"><a href="index.php?action=dashboard">Tableau de Bord</a></li>
                <li class="<?php echo ($act == 'saisie-besoin') ? 'active' : ''; ?>"><a href="index.php?action=saisie-besoin">Saisir un Besoin</a></li>
                <li class="<?php echo ($act == 'saisie-don') ? 'active' : ''; ?>"><a href="index.php?action=saisie-don">Saisir un Don</a></li>
                <li class="<?php echo ($act == 'liste-besoins-achats' || $act == 'simuler-achat') ? 'active' : ''; ?>"><a href="index.php?action=liste-besoins-achats">Acheter des Besoins</a></li>
                <li class="<?php echo ($act == 'recapitulatif') ? 'active' : ''; ?>"><a href="index.php?action=recapitulatif">Récapitulatif Financier</a></li>
                <li class="<?php echo ($act == 'config-frais') ? 'active' : ''; ?>"><a href="index.php?action=config-frais">Configuration</a></li>
            </ul>
        </nav>
    </header>
    <main>
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
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            width: 250px; 
            height: 100vh; 
            position: fixed; 
            top: 0;
            left: 0;
            padding: 30px 0;
            z-index: 1000;
        }
        nav {
            display: flex;
            flex-direction: column; 
            align-items: flex-start;
            height: 100%;
            padding: 0 20px;
        }
        .logo-container {
            margin-left: 1cm; /* Décalage de 6cm vers la droite */
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .logo-text {
            color: #007bff; /* Couleur bleu */
            font-weight: 800;
            font-size: 1.2rem;
            margin-top: 5px;
            letter-spacing: 1px;
        }
        nav ul {
            display: flex;
            flex-direction: column; 
            list-style: none;
            gap: 15px;
            margin: 0;
            padding: 0;
            width: 100%;
        }
        nav ul li {
            position: relative;
            width: fit-content;
        }
        nav ul li a {
            text-decoration: none;
            color: #444;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 8px 0;
            display: block;
            transition: color 0.3s ease;
        }
        nav ul li a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            bottom: 0;
            left: 0;
            background-color: #007bff;
            transition: width 0.3s ease;
            border-radius: 2px;
        }
        nav ul li a:hover {
            color: #007bff;
            padding-left: 5px; 
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
        main {
            margin-left: 270px; 
            padding: 20px;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo-container">
                <a href="index.php?action=dashboard" style="text-decoration: none; text-align: center;">
                    <img src="images/bngrc.png" alt="Logo BNGRC" style="height: 50px; width: auto; vertical-align: middle;">
                    <div class="logo-text">BNGRC</div>
                </a>
            </div>
            <ul>
                <?php $act = $_GET['action'] ?? 'dashboard'; ?>
                <li class="<?php echo ($act == 'dashboard') ? 'active' : ''; ?>"><a href="index.php?action=dashboard">📊Dashboard</a></li>
                <li class="<?php echo ($act == 'saisie-besoin') ? 'active' : ''; ?>"><a href="index.php?action=saisie-besoin">📋Besoins</a></li>
                <li class="<?php echo ($act == 'saisie-don') ? 'active' : ''; ?>"><a href="index.php?action=saisie-don">🎁Dons</a></li>
                <li class="<?php echo ($act == 'liste-besoins-achats' || $act == 'simuler-achat') ? 'active' : ''; ?>"><a href="index.php?action=liste-besoins-achats">🛒Acheter des Besoins</a></li>
                <li class="<?php echo ($act == 'recapitulatif') ? 'active' : ''; ?>"><a href="index.php?action=recapitulatif">📈Récapitulatif</a></li>
                <li class="<?php echo ($act == 'config-frais') ? 'active' : ''; ?>"><a href="index.php?action=config-frais">⚙️Configuration</a></li>
            </ul>
        </nav>
    </header>
    <main>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?> - BNGRC</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <a href="index.php?action=dashboard">Tableau de Bord</a> |
        <a href="index.php?action=saisie-besoin">Saisir Besoin</a> |
        <a href="index.php?action=saisie-don">Saisir Don</a>
    </nav>
    <hr>
    <main>
        <?php echo $content; ?>
    </main>
    <hr>
    <footer>
        <p><strong>Projet Final S3 - Février 2026</strong> [cite: 3]</p>
        <ul>
            <li>ETU004343 - Tsiferana</li>
            <li>ETU004368 - Nathie</li>
            <li>ETU004156 - Hery</li>
        </ul>
    </footer>
</body>
</html>
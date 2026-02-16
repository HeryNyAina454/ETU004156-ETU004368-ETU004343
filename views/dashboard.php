<?php include 'common/header.php'; ?>

<h2>Tableau de Bord des Distributions</h2>

<table border="1" style="width:100%; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th>Ville</th>
            <th>Article</th>
            <th>Besoin Initial</th>
            <th>Prix Unitaire</th>
            <th>Total Reçu</th>
            <th>Reste</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($donnees as $d): ?>
        <tr>
            <td><?php echo htmlspecialchars($d['nom_ville']); ?></td>
            <td><?php echo htmlspecialchars($d['article'] ?? '-'); ?></td>
            <td><?php echo $d['quantite_initiale'] ?? 0; ?></td>
            <td><?php echo number_format($d['prix_unitaire'] ?? 0, 2); ?> Ar</td>
            <td><?php echo $d['total_recu'] ?? 0; ?></td>
            <td><?php echo $d['quantite_restante'] ?? 0; ?></td>
            <td>
                <?php if($d['article']): ?>
                    <?php echo ($d['quantite_restante'] <= 0) ? '✅ Comblé' : '⏳ En attente'; ?>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'common/footer.php'; ?>
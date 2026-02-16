<?php include 'common/header.php'; ?>

<main>
    <h2>Simulation de l'Achat</h2>

    <div style="background: #f4f4f4; padding: 20px; border-radius: 10px;">
        <h3>Détails de l'opération</h3>
        <ul>
            <li><strong>Article :</strong> <?= $article ?></li>
            <li><strong>Quantité :</strong> <?= $qte_simulee ?> <?= $unite ?></li>
            <li><strong>Prix Unitaire :</strong> <?= number_format($prix_u, 2) ?> Ar</li>
            <li><strong>Frais d'achat (<?= $frais_taux ?>%) :</strong> <?= number_format($montant_frais, 2) ?> Ar</li>
            <li><strong>Total à payer :</strong> <span style="color:red; font-weight:bold;"><?= number_format($total_ttc, 2) ?> Ar</span></li>
        </ul>
    </div>

    <div style="display: flex; gap: 20px; margin-top: 20px;">
        <div style="flex: 1; border: 1px solid #ccc; padding: 15px;">
            <h4>Impact sur le Besoin</h4>
            <p>Reste actuel : <?= $reste_actuel ?></p>
            <p>Reste après achat : <strong><?= $reste_apres ?></strong></p>
        </div>

        <div style="flex: 1; border: 1px solid #ccc; padding: 15px;">
            <h4>Impact sur les Dons (Argent)</h4>
            <p>Argent disponible : <?= number_format($argent_actuel, 2) ?> Ar</p>
            <p>Argent après achat : <strong><?= number_format($argent_apres, 2) ?> Ar</strong></p>
        </div>
    </div>

    <div style="margin-top: 30px;">
        <?php if ($argent_apres >= 0): ?>
            <form action="index.php?action=valider-achat" method="POST">
                <input type="hidden" name="id_besoin" value="<?= $id_besoin ?>">
                <input type="hidden" name="qte" value="<?= $qte_simulee ?>">
                <button type="submit" style="background-color: green; color: white; padding: 10px 20px;">Confirmer l'achat (Validation finale)</button>
            </form>
        <?php else: ?>
            <div style="color: red; font-weight: bold; border: 2px solid red; padding: 10px;">
                ⚠️ SOLDE INSUFFISANT : Vous ne pouvez pas valider cet achat.
            </div>
        <?php endif; ?>
        
        <br>
        <a href="index.php?action=liste-besoins-achats">⬅️ Annuler et revenir à la liste</a>
    </div>
</main>

<?php include 'common/footer.php'; ?>
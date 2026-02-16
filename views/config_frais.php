<?php include 'common/header.php'; ?>

<main>
    <h2>Configuration du Système</h2>

    <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
        <div style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 20px;">
            ✅ Frais d'achat mis à jour avec succès !
        </div>
    <?php endif; ?>

    <form action="index.php?action=save-config" method="POST" style="background: #f9f9f9; padding: 20px; border-radius: 8px;">
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px;">Frais d'achat (en %) :</label>
            <input type="number" name="frais_achat" step="0.01" value="<?= $frais_actuel ?>" style="padding: 8px; width: 100px;">
            <p><small>Ce pourcentage sera ajouté au prix unitaire lors de chaque achat via les dons en argent.</small></p>
        </div>

        <button type="submit" style="padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer;">
            Enregistrer la configuration
        </button>
    </form>
</main>

<?php include 'common/footer.php'; ?>
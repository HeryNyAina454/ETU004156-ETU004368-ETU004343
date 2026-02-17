<?php include 'common/header.php'; ?>

<main>
    <form action="index.php?action=save-config" method="POST">
        <h2>Configuration du Système</h2>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <div style="color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; padding: 10px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem;">
                ✅ Frais d'achat mis à jour avec succès !
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label>Frais d'achat (en %) :</label>
            <input type="number" name="frais_achat" step="0.01" value="<?= $frais_actuel ?>" required>
            <p style="margin-top: 10px; font-size: 0.8rem; color: var(--secondary);">
                Ce pourcentage sera ajouté au prix unitaire lors de chaque achat via les dons en argent.
            </p>
        </div>

        <button type="submit">Enregistrer la configuration</button>
    </form>
</main>

<?php include 'common/footer.php'; ?>
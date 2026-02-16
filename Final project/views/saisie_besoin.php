<?php include 'common/header.php'; ?>

<main>
    <h2>Expression des besoins des sinistrés</h2>
    <form action="index.php?action=save-besoin" method="POST">
        
        <div class="form-group">
            <label>Catégorie de besoin :</label>
            <select name="id_categorie" required>
                <option value="1">Alimentation</option>
                <option value="2">Matériaux de construction</option>
                <option value="3">Aide financière</option>
                <option value="4">Autres</option>
            </select>
        </div>

        <div class="form-group">
            <label>Ville des sinistrés :</label>
            <select name="id_ville" required>
                <?php while($v = $villes->fetch(PDO::FETCH_ASSOC)): ?>
                    <option value="<?php echo $v['id_ville']; ?>"><?php echo $v['nom_ville']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Article :</label>
            <select name="article" required>
                <option value="Riz">Riz (Vary)</option>
                <option value="Huile">Huile (Menaka)</option>
                <option value="Savon">Savon (Savony)</option>
                <option value="Tôle">Tôle (Fanitso)</option>
                <option value="Clou">Clou (Fantsika)</option>
                <option value="Argent">Argent (Vola)</option>
            </select>
        </div>

        <div class="form-group" style="display: flex; flex-direction: row; gap: 10px;">
            <div style="flex: 2;">
                <label>Quantité :</label>
                <input type="number" step="0.01" name="quantite" required>
            </div>
            <div style="flex: 1;">
                <label>Unité :</label>
                <select name="unite" required>
                    <option value="kg">kg</option>
                    <option value="sac">sac</option>
                    <option value="litre">litre</option>
                    <option value="pièce">pièce (tôle)</option>
                    <option value="carton">carton (clou)</option>
                    <option value="MGA">MGA (Ariary)</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Prix Unitaire (Ar) :</label>
            <input type="number" step="0.01" name="prix_unitaire" required>
        </div>

        <button type="submit">Enregistrer la demande</button>
    </form>
</main>

<?php include 'common/footer.php'; ?>
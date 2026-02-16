<?php include 'common/header.php'; ?>

<main>
    <h2>Réception d'un Don</h2>
    <form action="index.php?action=save-don" method="POST">
        
        <div class="form-group">
            <label>Catégorie :</label>
            <select name="id_categorie" required>
                <option value="1">Alimentation</option>
                <option value="2">Matériaux</option>
                <option value="3">Argent</option>
                <option value="4">Autres</option>
            </select>
        </div>

        <div class="form-group">
            <label>Article reçu :</label>
            <input type="text" name="article" placeholder="ex: Riz, Huile..." required>
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
                    <option value="pièce">pièce</option>
                    <option value="carton">carton</option>
                    <option value="MGA">MGA</option>
                </select>
            </div>
        </div>

        <button type="submit">Valider et Distribuer</button>
    </form>
</main>

<?php include 'common/footer.php'; ?>
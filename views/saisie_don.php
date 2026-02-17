<?php include 'common/header.php'; ?>

<main>
    <form action="index.php?action=save-don" method="POST">
        <h2>Réception d'un Don</h2>
        
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

        <div class="form-group">
            <label>Ordre de distribution :</label>
            <select name="type_distribution" required>
                <option value="prioritaire">Ordre Prioritaire (FIFO)</option>
                <option value="decroissant">Ordre Décroissant (Plus petite demande)</option>
                <option value="proportionnel">Ordre Proportionnel</option>
            </select>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 10px;">
            <button type="submit" style="flex: 2;">Valider et Distribuer</button>
            <a href="index.php?action=reinitialiser" onclick="return confirm('Êtes-vous sûr de vouloir tout réinitialiser ?');" style="flex: 1; text-decoration: none;">
                <button type="button" style="width: 100%; background-color: #dc3545; margin-top: 0;">Réinitialiser</button>
            </a>
        </div>
    </form>
</main>

<script>
document.querySelector('form').onsubmit = function() {
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerText = "Calcul de distribution...";
};
</script>

<?php include 'common/footer.php'; ?>
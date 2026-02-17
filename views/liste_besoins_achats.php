<?php include 'common/header.php'; ?>

<main>
    <div>
        <h2>Besoins restants et Achats</h2>

        <div class="filter-section">
            <form action="index.php?action=liste-besoins-achats" method="POST">
                <label>Filtrer par ville :</label>
                <input type="text" name="ville_filtre" placeholder="Nom de la ville...">
                <button type="submit">Filtrer</button>
            </form>
        </div>

        <table border="1" style="width: 100%; margin-top: 20px; border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Ville</th>
                    <th>Article</th>
                    <th>Reste à combler</th>
                    <th>Unité</th>
                    <th>Prix Unitaire (Ar)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($besoins as $b): ?>
                <tr>
                    <td><?= $b['ville'] ?></td>
                    <td><?= $b['article'] ?></td>
                    <td><?= $b['quantite_restante'] ?></td>
                    <td><?= $b['unite'] ?></td>
                    <td><?= number_format($b['prix_unitaire'], 2, ',', ' ') ?></td>
                    <td>
                        <button onclick="ouvrirSimulation(
                            <?= $b['id_besoin'] ?>, 
                            '<?= $b['article'] ?>', 
                            <?= $b['quantite_restante'] ?>, 
                            <?= $b['prix_unitaire'] ?>
                        )">Acheter / Simuler</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="modalSimulation" style="display:none; position:fixed; top:20%; left:30%; background:#fff; padding:20px; border:2px solid #000; z-index:1000;">
        <h3>Simulation d'achat</h3>
        <p id="sim_article"></p>
        <label>Quantité à acheter :</label>
        <input type="number" id="input_quantite" oninput="calculerTotal()">
        
        <div style="margin-top:15px; background:#f9f9f9; padding:10px;">
            <p>Sous-total : <span id="res_sous_total">0</span> Ar</p>
            <p>Frais (<?= $frais_achat ?>%) : <span id="res_frais">0</span> Ar</p>
            <hr>
            <p><strong>Total à déduire de l'Argent : <span id="res_total">0</span> Ar</strong></p>
        </div>

        <div id="msg_erreur" style="color:red;"></div>

        <button onclick="validerAchat()">Confirmer l'achat</button>
        <button onclick="document.getElementById('modalSimulation').style.display='none'">Annuler</button>
    </div>
</main>

<script>
let currentBesoin = {};
const TAUX_FRAIS = <?= $frais_achat ?> / 100;

function ouvrirSimulation(id, art, reste, prix) {
    currentBesoin = { id, art, reste, prix };
    document.getElementById('sim_article').innerText = "Article : " + art + " (Max: " + reste + ")";
    document.getElementById('input_quantite').value = reste;
    document.getElementById('modalSimulation').style.display = 'block';
    document.getElementById('msg_erreur').innerText = "";
    calculerTotal();
}

function calculerTotal() {
    let qte = parseFloat(document.getElementById('input_quantite').value) || 0;
    if (qte > currentBesoin.reste) qte = currentBesoin.reste;
    
    let sousTotal = qte * currentBesoin.prix;
    let frais = sousTotal * TAUX_FRAIS;
    let total = sousTotal + frais;

    document.getElementById('res_sous_total').innerText = sousTotal.toLocaleString();
    document.getElementById('res_frais').innerText = frais.toLocaleString();
    document.getElementById('res_total').innerText = total.toLocaleString();
}

function validerAchat() {
    const qte = document.getElementById('input_quantite').value;
    window.location.href = `index.php?action=valider-achat&id_besoin=${currentBesoin.id}&qte=${qte}`;
}
</script>

<?php include 'common/footer.php'; ?>
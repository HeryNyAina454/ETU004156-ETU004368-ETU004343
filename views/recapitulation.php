<?php include 'common/header.php'; ?>

<main style="padding: 20px;">
    <h2>Récapitulation des Besoins (Montants en Ar)</h2>
    
    <div style="display: flex; gap: 20px; margin-bottom: 20px;">
        <div style="flex: 1; padding: 20px; background: #e3f2fd; border-radius: 8px; border: 1px solid #bbdefb;">
            <h3 style="margin-top:0;">Besoins Totaux</h3>
            <p id="total_montant" style="font-size: 24px; font-weight: bold;">--</p>
        </div>
        <div style="flex: 1; padding: 20px; background: #e8f5e9; border-radius: 8px; border: 1px solid #c8e6c9;">
            <h3 style="margin-top:0;">Besoins Satisfaits</h3>
            <p id="satisfait_montant" style="font-size: 24px; font-weight: bold; color: green;">--</p>
        </div>
        <div style="flex: 1; padding: 20px; background: #ffebee; border-radius: 8px; border: 1px solid #ffcdd2;">
            <h3 style="margin-top:0;">Besoins Restants</h3>
            <p id="restant_montant" style="font-size: 24px; font-weight: bold; color: red;">--</p>
        </div>
    </div>

    <button onclick="chargerRecap()" id="btn_actualiser" style="padding: 10px 20px; cursor: pointer; background: #333; color: white; border: none; border-radius: 4px;">
        Actualiser (AJAX)
    </button>
</main>

<script>
function chargerRecap() {
    const btn = document.getElementById('btn_actualiser');
    btn.innerText = "Chargement...";
    
    // On force le chemin vers la racine du projet
fetch('ajax/recap_data.php')
        .then(response => {
            if (!response.ok) throw new Error('Fichier AJAX introuvable');
            return response.json();
        })
        .then(data => {
            console.log("Données reçues :", data); // Pour debugger dans la console F12
            document.getElementById('total_montant').innerText = formatMoney(data.total);
            document.getElementById('satisfait_montant').innerText = formatMoney(data.satisfait);
            document.getElementById('restant_montant').innerText = formatMoney(data.restant);
            btn.innerText = "Actualiser (AJAX)";
        })
        .catch(error => {
            console.error('Erreur:', error);
            btn.innerText = "Erreur de chargement";
            btn.style.background = "red";
        });
}

function formatMoney(amount) {
    return new Intl.NumberFormat('fr-FR').format(amount || 0) + " Ar";
}

// Chargement automatique
window.onload = chargerRecap;
</script>

<?php include 'common/footer.php'; ?>
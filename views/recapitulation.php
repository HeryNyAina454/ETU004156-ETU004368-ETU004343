<?php include 'common/header.php'; ?>

<main>
    <div>
        <h2>Récapitulation des Besoins (Montants en Ar)</h2>
        
        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
            <div style="flex: 1; padding: 20px; background: #e3f2fd; border-radius: 8px; border: 1px solid #bbdefb;">
                <h3 style="margin-top:0; color: #1e293b; font-size: 1rem;">Besoins Totaux</h3>
                <p id="total_montant" style="font-size: 20px; font-weight: bold; color: #1e293b;">--</p>
            </div>
            <div style="flex: 1; padding: 20px; background: #e8f5e9; border-radius: 8px; border: 1px solid #c8e6c9;">
                <h3 style="margin-top:0; color: #1e293b; font-size: 1rem;">Besoins Satisfaits</h3>
                <p id="satisfait_montant" style="font-size: 20px; font-weight: bold; color: green;">--</p>
            </div>
            <div style="flex: 1; padding: 20px; background: #ffebee; border-radius: 8px; border: 1px solid #ffcdd2;">
                <h3 style="margin-top:0; color: #1e293b; font-size: 1rem;">Besoins Restants</h3>
                <p id="restant_montant" style="font-size: 20px; font-weight: bold; color: red;">--</p>
            </div>
        </div>

        <button onclick="chargerRecap()" id="btn_actualiser">
            Actualiser (AJAX)
        </button>
    </div>
</main>

<script>
function chargerRecap() {
    const btn = document.getElementById('btn_actualiser');
    btn.innerText = "Chargement...";
    
    fetch('ajax/recap_data.php')
        .then(response => {
            if (!response.ok) throw new Error('Fichier AJAX introuvable');
            return response.json();
        })
        .then(data => {
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

window.onload = chargerRecap;
</script>

<?php include 'common/footer.php'; ?>
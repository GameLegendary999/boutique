document.addEventListener('DOMContentLoaded', () => {
    chargerPanier();
});

function chargerPanier() {
    fetch('php/get_panier.php')
        .then(response => response.json())
        .then(panier => {
            const panierDiv = document.getElementById('panier');

            panierDiv.innerHTML = ''; // Réinitialiser le contenu
            if (panier.length === 0) {
                panierDiv.innerHTML = '<p>Votre panier est vide.</p>';
                return;
            }

            panier.forEach(article => {
                panierDiv.innerHTML += `
                    <div class="produit">
                        <h2>${article.nom}</h2>
                        <p>Prix : ${article.prix} €</p>
                        <p>
                            Quantité :
                            <input 
                                type="number" 
                                value="${article.quantité}" 
                                min="1" 
                                max="${article.stock_disponible}" 
                                onchange="modifierQuantite(${article.id_produit}, this)">
                        </p>
                        <p>Total : ${article.prix * article.quantité} €</p>
                        <button onclick="supprimerArticle(${article.id_produit})">Supprimer</button>
                    </div>`;
            });

            panierDiv.innerHTML += `<button onclick="viderPanier()">Vider le panier</button>`;
        })
        .catch(error => console.error('Erreur lors du chargement du panier :', error));
}

function modifierQuantite(id_produit, inputElement) {
    const quantite = parseInt(inputElement.value);

    if (quantite <= 0) {
        alert("La quantité doit être supérieure à 0.");
        inputElement.value = 1; // Réinitialise à 1 si la quantité est invalide
        return;
    }

    fetch('php/ajouter_panier.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_produit: id_produit, quantite: quantite })
    })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert(data.message); // Affiche un message d'erreur si la quantité dépasse le stock
                inputElement.value = data.quantite_disponible; // Met à jour avec la valeur correcte
            } else {
                chargerPanier(); // Recharge le panier après la mise à jour
            }
        })
        .catch(error => console.error('Erreur lors de la modification de la quantité :', error));
}

function supprimerArticle(id_produit) {
    fetch('php/supprimer_panier.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_produit: id_produit })
    })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            chargerPanier();
        })
        .catch(error => console.error('Erreur lors de la suppression de l\'article :', error));
}



function viderPanier() {
    fetch('php/supprimer_panier.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'vider' })
    })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            chargerPanier();
        })
        .catch(error => console.error('Erreur lors du vidage du panier :', error));
}

document.addEventListener('DOMContentLoaded', () => {
    chargerPanier();
});


function afficherTotalGeneral() {
    fetch('php/get_total_articles.php') // Appelle le fichier PHP
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour la section Résumé avec le total général
                const resumeDiv = document.querySelector('.resume-content');
                resumeDiv.innerHTML = `
                    <p><strong>Total général :</strong> ${data.total_general.toFixed(2)} €</p>
                `;
            } else {
                console.error("Erreur :", data.message);
            }
        })
        .catch(error => console.error("Erreur lors de la récupération du total général :", error));
}

// Charger automatiquement le total toutes les 2 secondes
setInterval(() => {
    afficherTotalGeneral();
}, 2000);

// Appeler lors du chargement initial
document.addEventListener('DOMContentLoaded', () => {
    afficherTotalGeneral();
});
// Function to fetch and display the 3 latest orders
function chargerDernieresCommandes() {
    fetch('php/recuperer_commandes.php') // Call the API endpoint
        .then(response => response.json())
        .then(data => {
            const commandesContainer = document.getElementById('liste-commandes');
            commandesContainer.innerHTML = ''; // Clear previous content

            if (data.success) {
                // Loop through orders and display them
                data.commandes.forEach(commande => {
                    const commandeDiv = document.createElement('div');
                    commandeDiv.className = 'commande';
                    commandeDiv.innerHTML = `
                        <p><strong>Description :</strong> ${commande.description}</p>
                        <p><strong>Total :</strong> ${commande.total} €</p>
                        <p><strong>Date :</strong> ${commande.date}</p>
                    `;
                    commandesContainer.appendChild(commandeDiv);
                });
            } else {
                commandesContainer.innerHTML = `<p>${data.message}</p>`;
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement des commandes :', error);
            const commandesContainer = document.getElementById('liste-commandes');
            commandesContainer.innerHTML = `<p style="color: red;">Erreur lors de la récupération des commandes.</p>`;
        });
}

// Load orders when the page is fully loaded
document.addEventListener('DOMContentLoaded', chargerDernieresCommandes);

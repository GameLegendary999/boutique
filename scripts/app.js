document.addEventListener('DOMContentLoaded', () => {
    fetch('php/products.php')
        .then(response => response.json())
        .then(products => {
            const produitsDiv = document.getElementById('produits');
            if (products.length === 0) {
                produitsDiv.innerHTML = '<p>Aucun produit disponible pour le moment.</p>';
                return;
            }

            products.forEach(product => {
                produitsDiv.innerHTML += `
                    <div class="produit">
                        <img src="${product.image_path}" alt="${product.nom}" class="produit-image">
                        <h2>${product.nom}</h2>
                        <p>${product.description}</p>
                        <p>Stock disponible : ${product.stock}</p>
                        <p>Prix : ${product.prix} €</p>
                        <button onclick="ajouterAuPanier(${product.id_produit})">Ajouter au panier</button>
                    </div>`;
            });
        })
        .catch(error => console.error('Erreur lors de la récupération des produits :', error));
});

function ajouterAuPanier(id_produit) {
    fetch('php/ajouter_panier.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_produit: id_produit, quantite: 1 })
    })
        .then(response => response.json())
        .then(data => {
            alert(data.message); // Confirme l'ajout au panier
        })
        .catch(error => console.error('Erreur lors de l\'ajout au panier :', error));
}

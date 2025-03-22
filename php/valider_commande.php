<?php
session_start();
require 'db.php'; // Connexion à la base de données

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    die('<p style="color: red;">Erreur : Vous devez être connecté pour valider votre panier.</p>');
}

$id_client = $_SESSION['user']['id'];

// Étape 1 : Récupérer les produits du panier
$stmt = $pdo->prepare("
    SELECT pa.id_produit, pa.quantité AS quantite_commande, p.nom, p.prix, p.stock
    FROM panier pa
    JOIN produits p ON pa.id_produit = p.id_produit
    WHERE pa.id_client = ?
");
$stmt->execute([$id_client]);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$articles) {
    die('<p style="color: red;">Erreur : Votre panier est vide.</p>');
}

// Étape 2 : Valider les stocks et mettre à jour la table produits
foreach ($articles as $article) {
    $id_produit = $article['id_produit'];
    $quantite_commande = $article['quantite_commande'];
    $stock_actuel = $article['stock'];

    if ($stock_actuel < $quantite_commande) {
        die('<p style="color: red;">Erreur : Stock insuffisant pour le produit ' . $article['nom'] . '.</p>');
    }

    // Réduire le stock
    $nouveau_stock = $stock_actuel - $quantite_commande;
    $update_stock = $pdo->prepare("UPDATE produits SET stock = ? WHERE id_produit = ?");
    $update_stock->execute([$nouveau_stock, $id_produit]);
}

// Étape 3 : Construire la description et calculer le total
$description = '';
$total = 0;

foreach ($articles as $article) {
    $description .= $article['nom'] . ' (x' . $article['quantite_commande'] . '), ';
    $total += $article['prix'] * $article['quantite_commande'];
}

$description = rtrim($description, ', '); // Retirer la dernière virgule

// Étape 4 : Insérer la commande dans la table `commandes`
$stmt = $pdo->prepare("INSERT INTO commandes (id_client, description, total) VALUES (?, ?, ?)");
$stmt->execute([$id_client, $description, $total]);

// Étape 5 : Vider le panier après validation
$stmt = $pdo->prepare("DELETE FROM panier WHERE id_client = ?");
$stmt->execute([$id_client]);

// Répondre avec un message de validation
echo '<div style="display: flex; justify-content: center; align-items: center; height: 100vh; text-align: center;">
        <p style="color: green; font-size: 24px; font-weight: bold;">
            Votre commande a été passée avec succès !<br>
            Vous allez être redirigé vers le tableau de bord...
        </p>
      </div>';

// Rediriger automatiquement après 5 secondes
echo '<script>
    setTimeout(function() {
        window.location.href = "../dashboard.html";
    }, 5000);
</script>';

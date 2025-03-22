<?php
session_start();
require 'db.php'; // Connexion à la base de données
header('Content-Type: application/json');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Vous devez être connecté pour ajouter au panier']);
    exit;
}

// Récupérer les données envoyées par le client
$data = json_decode(file_get_contents('php://input'), true);
$id_client = $_SESSION['user']['id'];
$id_produit = $data['id_produit'];
$quantite = $data['quantite'];

// Récupérer le prix du produit depuis la table `produits`
$stmt = $pdo->prepare("SELECT prix FROM produits WHERE id_produit = ?");
$stmt->execute([$id_produit]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    echo json_encode(['success' => false, 'message' => 'Produit introuvable']);
    exit;
}

$prix = $produit['prix'];

// Vérifier si l'article existe déjà dans le panier
$stmt = $pdo->prepare("SELECT * FROM panier WHERE id_client = ? AND id_produit = ?");
$stmt->execute([$id_client, $id_produit]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if ($article) {
    // Met à jour la quantité de l'article dans le panier
    $nouvelle_quantite = $article['quantité'] + $quantite;
    $stmt = $pdo->prepare("UPDATE panier SET quantité = ?, prix = ? WHERE id_client = ? AND id_produit = ?");
    $stmt->execute([$nouvelle_quantite, $prix, $id_client, $id_produit]);
} else {
    // Ajouter un nouvel article dans le panier
    $stmt = $pdo->prepare("INSERT INTO panier (id_client, id_produit, quantité, prix, date_ajout) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$id_client, $id_produit, $quantite, $prix]);
}

echo json_encode(['success' => true, 'message' => 'Article ajouté au panier']);

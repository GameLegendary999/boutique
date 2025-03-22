<?php
session_start();
require 'db.php'; // Connexion à la base de données
header('Content-Type: application/json');

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    echo json_encode([]);
    exit;
}

$id_client = $_SESSION['user']['id'];

// Récupérer les articles dans le panier pour le client connecté
$stmt = $pdo->prepare("
    SELECT p.id_produit, p.nom, p.prix, panier.quantité 
    FROM panier 
    JOIN Produits p ON panier.id_produit = p.id_produit 
    WHERE panier.id_client = ?
");
$stmt->execute([$id_client]);
$panier = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retourner les données sous forme JSON
echo json_encode($panier);
?>

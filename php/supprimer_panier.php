<?php
session_start();
require 'db.php'; // Connexion à la base de données
header('Content-Type: application/json');

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Vous devez être connecté pour supprimer des articles']);
    exit;
}

$id_client = $_SESSION['user']['id']; // ID du client connecté
$data = json_decode(file_get_contents('php://input'), true);

// Vérifie si une action spécifique est demandée (supprimer un article ou vider le panier)
if (isset($data['id_produit'])) {
    // Supprimer un article spécifique
    $id_produit = $data['id_produit'];
    $stmt = $pdo->prepare("DELETE FROM panier WHERE id_client = ? AND id_produit = ?");
    $stmt->execute([$id_client, $id_produit]);
    echo json_encode(['success' => true, 'message' => 'Article supprimé du panier']);
} elseif (isset($data['action']) && $data['action'] === 'vider') {
    // Vider tout le panier
    $stmt = $pdo->prepare("DELETE FROM panier WHERE id_client = ?");
    $stmt->execute([$id_client]);
    echo json_encode(['success' => true, 'message' => 'Panier vidé']);
} else {
    echo json_encode(['success' => false, 'message' => 'Aucune action reconnue']);
}

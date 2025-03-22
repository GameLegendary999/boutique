<?php
session_start();
require 'db.php'; // Connexion à la base de données
header('Content-Type: application/json');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté', 'total_general' => 0]);
    exit;
}

$id_client = $_SESSION['user']['id'];

// Récupérer le total par article (prix * quantité) et additionner les totaux
$stmt = $pdo->prepare("
    SELECT SUM(prix * quantité) AS total_general 
    FROM panier 
    WHERE id_client = ?
");
$stmt->execute([$id_client]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Assurer que total_general soit un nombre
$total_general = (float) ($result['total_general'] ?? 0);

// Retourner le total général sous forme de JSON
echo json_encode(['success' => true, 'total_general' => $total_general]);

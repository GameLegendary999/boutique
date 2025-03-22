<?php
session_start();
require 'db.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Veuillez vous connecter pour voir vos commandes.']);
    exit();
}

$id_client = $_SESSION['user']['id'];

// Fetch the 3 most recent orders for the client
$stmt = $pdo->prepare("
    SELECT id_commande, description, total, date 
    FROM commandes 
    WHERE id_client = ? 
    ORDER BY date DESC 
    LIMIT 3
");
$stmt->execute([$id_client]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return JSON response
header('Content-Type: application/json');
if (!empty($commandes)) {
    echo json_encode(['success' => true, 'commandes' => $commandes]);
} else {
    echo json_encode(['success' => false, 'message' => 'Aucune commande trouvée.']);
}

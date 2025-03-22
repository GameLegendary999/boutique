<?php
require 'db.php'; // Inclure la connexion

// Récupérer tous les produits depuis la table Produits
$stmt = $pdo->query("SELECT * FROM Produits");
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retourner les produits en JSON
header('Content-Type: application/json');
echo json_encode($produits);
?>
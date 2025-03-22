<?php
require 'db.php';

// Récupération des données envoyées par le front-end
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['nom'], $data['email'], $data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes']);
    exit;
}

$nom = $data['nom'];
$email = $data['email'];
$mot_de_passe = $data['password'];

// Vérification de l'email existant
$stmt = $pdo->prepare("SELECT * FROM clients WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Email déjà utilisé']);
    exit;
}

// Insertion d'un nouvel utilisateur
$stmt = $pdo->prepare("INSERT INTO clients (nom, email, mot_de_passe) VALUES (?, ?, ?)");
$stmt->execute([$nom, $email, $mot_de_passe]);
echo json_encode(['success' => true, 'message' => 'Inscription réussie']);

<?php
require 'db.php';
session_start();

// Récupération des données envoyées par le front-end
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['email'], $data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes ou invalides']);
    exit;
}

$email = $data['email'];
$mot_de_passe = $data['password'];

// Vérification de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM clients WHERE email = ?");
$stmt->execute([$email]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);

if ($client) {
    if ($client['mot_de_passe'] === $mot_de_passe) {
        $_SESSION['user'] = [
            'id' => $client['id_client'],
            'nom' => $client['nom'],
            'email' => $client['email']
        ];
        echo json_encode(['success' => true, 'redirect' => 'dashboard.html']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Mot de passe incorrect']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Utilisateur introuvable']);
}

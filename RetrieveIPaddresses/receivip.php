<?php

// Connexion à la base de données (à personnaliser avec vos propres informations de connexion)
 
 
$servername = "localhost";
$username = "htmc";
$password = "gjetfm26";
$dbname = "ip_db";
 
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Recevoir les données JSON
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if ($data !== null && isset($data['ip'])) {
    // Préparer la requête SQL d'insertion
    $ip_address = $conn->real_escape_string($data['ip']);
    $servername = $conn->real_escape_string($data['servername']);
    $timestamp = date('Y-m-d H:i:s');  // Obtenez le timestamp actuel
    
$sql ='';

if ($servername !== null ){
$sql = "INSERT INTO table_ip (ip_address, timestamp, servername) VALUES ('$ip_address', '$timestamp','$servername')";
}else{
$sql = "INSERT INTO table_ip (ip_address, timestamp) VALUES ('$ip_address', '$timestamp')";
}
    

    // Exécuter la requête SQL
    if ($conn->query($sql) === TRUE) {
        // Répondre avec succès
        $response = ['status' => 'success', 'message' => 'IP enregistrée avec succès'];
    } else {
        // Gérer les erreurs d'insertion
        $response = ['status' => 'error', 'message' => 'Erreur lors de l\'enregistrement de l\'IP : ' . $conn->error];
    }
} else {
    // Gérer les erreurs de décodage JSON ou de données manquantes
    $response = ['status' => 'error', 'message' => 'Données JSON non valides ou adresse IP manquante'];
}

// Fermer la connexion à la base de données
$conn->close();

// Retourner la réponse en format JSON
header('Content-Type: application/json');
echo json_encode($response);

?>


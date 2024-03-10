<?php
// Utilisation de l'API d'un service tiers pour obtenir l'adresse IP publique
$api_url = 'https://api64.ipify.org?format=json';

// Obtenez le nom d'hôte
$hostname = gethostname();

// Effectuer une requête HTTP pour récupérer les données
$response = file_get_contents($api_url);

// $remote_url = 'https://iserver/receivip.php';

$remote_url = 'http://localhost/ip/receivip.php';


// Vérifier si la requête a réussi
if ($response !== false) {
    // Convertir la réponse JSON en tableau associatif
    // Décoder la réponse JSON en un tableau associatif
    $data = json_decode($response, true);
    // Ajouter une clé et une valeur au tableau associatif
    $data['servername'] = $hostname;

// Re-encoder le tableau associatif en JSON
$new_response = json_encode($data);

    // Vérifier si la conversion a réussi
    if ($data !== null) {
        // Récupérer l'adresse IP publique
        $public_ip = $data['ip'];
        
        
$options = [
    'http' => [
        'method'  => 'POST',
        'header'  => 'Content-type: application/json',
        'content' => $new_response,
    ],
];

$context  = stream_context_create($options);
$result = file_get_contents($remote_url, false, $context);

echo 'result : '.$result;


echo 'http://' . $public_ip."";
        // Afficher l'adresse IP publique
   /*     
echo '<a href="https://' . $public_ip . ':443">';
echo 'https://' . $public_ip.":443";
echo '</a>';
echo '<br/>';


echo '<a href="http://' . $public_ip . ':80">';
echo 'http://' . $public_ip.":80";
echo '</a>';
echo '<br/>';


echo 'http://' . $public_ip."";

*/
    } else {
        echo 'Erreur lors de la conversion de la réponse JSON.';
    }
} else {
    echo 'Erreur lors de la requête HTTP vers le service.';
}
?>

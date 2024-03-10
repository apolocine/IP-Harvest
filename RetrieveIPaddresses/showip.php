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

// Supprimer les enregistrements obsolètes (par exemple, supprimer tous les enregistrements plus anciens que 24 heures)
$expiry_time = date('Y-m-d H:i:s', strtotime('-24 hours'));
$sql_delete = "DELETE FROM table_ip WHERE timestamp < '$expiry_time'";
$conn->query($sql_delete);

// Traitement de la suppression si un identifiant est fourni dans l'URL
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql_delete = "DELETE FROM table_ip WHERE id = $delete_id";
    $conn->query($sql_delete);
}


// Requête SQL SELECT pour récupérer les adresses IP enregistrées
$sql = "SELECT id, ip_address, timestamp, servername  FROM table_ip";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Afficher les résultats dans un tableau HTML
    echo "<table><tr><th>Server Name </th> <th>Adresse IP</th><th>Timestamp</th></tr>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>" ;
        
        echo "<td>" . $row["servername"] . "</td>";
        
        echo "<td>" ;
        echo '<a href="https://' . $row["ip_address"]  . ':443">';
 	echo 'https://' . $row["ip_address"] .":443";
 	echo '</a>';
        echo "</td>";   
         
        echo "<td>" . $row["timestamp"] . "</td>";
        
        echo "<td>";
        echo "<a href='?delete_id=" . $row["id"] . "'>X</a>";
        echo "</td>";
            
    echo "</tr>";       
        
    }

    echo "</table>";
} else {
    echo "Aucune adresse IP enregistrée dans la base de données.";
}

// Fermer la connexion à la base de données
$conn->close();

?>


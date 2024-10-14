<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'termes_jeunes_db';
$username = 'hugo';
$password = 'Hugo290210120510!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Préparer et exécuter la requête pour récupérer le contexte
        $stmt = $pdo->prepare("SELECT contexte FROM termes WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo htmlspecialchars($result['contexte']);
        } else {
            echo "Contexte non trouvé.";
        }
    } else {
        echo "Aucun terme sélectionné.";
    }
} catch (PDOException $e) {
    echo "Erreur lors de la récupération du contexte : " . $e->getMessage();
}
?>

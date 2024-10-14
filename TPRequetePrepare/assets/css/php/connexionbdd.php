<?php

$host = 'localhost';
$dbname = 'termes_jeunes_db';
$username = 'hugo';
$password = 'Hugo290210120510!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des termes pour la liste déroulante
    $stmt = $pdo->query("SELECT id, terme FROM termes");
    $termes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Initialisation de la variable pour le contexte
    $contexte = '';

    // Gestion de la sélection du terme
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['terme'])) {
        $termeId = $_POST['terme'];

        // Récupération du contexte correspondant au terme sélectionné
        $stmt = $pdo->prepare("SELECT contexte FROM termes WHERE id = :id");
        $stmt->bindParam(':id', $termeId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification si un contexte a été trouvé
        if ($result) {
            $contexte = htmlspecialchars($result['contexte']);
        } else {
            $contexte = "Aucun contexte trouvé.";
        }
    }
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des termes : " . $e->getMessage();
}
?>
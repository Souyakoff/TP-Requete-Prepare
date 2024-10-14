<?php
$host = 'localhost';
$dbname = 'termes_jeunes_db';
$username = 'hugo';
$password = 'Hugo290210120510!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO termes (terme, contexte, exemples) VALUES (:terme, :contexte, :exemples)");

    $stmt->bindParam(':terme', $_POST['terme'], PDO::PARAM_STR);
    $stmt->bindParam(':contexte', $_POST['contexte'], PDO::PARAM_STR);
    $stmt->bindParam(':exemples', $_POST['exemples'], PDO::PARAM_STR);

    $stmt->execute();

    echo "La traduction a été ajoutée avec succès.";
} catch (PDOException $e) {
    echo "Erreur lors de la connexion ou de l'insertion : " . $e->getMessage();
}
?>


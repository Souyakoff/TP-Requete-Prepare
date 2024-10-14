<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'termes_jeunes_db';
$username = 'hugo';
$password = 'Hugo290210120510!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Chemin vers le fichier CSV
    $csvFile = 'termes_utilises_par_les_jeunes.csv';

    // Ouverture du fichier CSV
    if (($handle = fopen($csvFile, "r")) !== FALSE) {
        $csvData = [];

        // Lecture de la première ligne (en-têtes)
        $headers = fgetcsv($handle, 1000, ',');

        // Lecture des lignes suivantes et stockage dans un tableau associatif
        while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $csvData[] = array_combine($headers, $row);
        }

        // Fermeture du fichier CSV
        fclose($handle);

        // Conversion en JSON
        $jsonData = json_encode($csvData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Affichage ou sauvegarde du JSON
        echo $jsonData;
    }

    // Interprétation du JSON $jsonData, et transformation en tableau associatif
    $listeTermesJSON = json_decode($jsonData, true);

    // Préparation de la requête SQL
    $stmt = $pdo->prepare("INSERT INTO termes (terme, contexte, exemples) VALUES (:terme, :contexte, :exemples)");

    // Parcours du JSON
    foreach ($listeTermesJSON as $termeEnCours) {
        // Récupération des valeurs
        $terme = $termeEnCours['terme'];
        $contexte = $termeEnCours['contexte'];
        $exemples = $termeEnCours['exemples'];
        echo "Terme en cours: $terme\n";

        // Requête préparée, étape 2 : lie les valeurs aux paramètres de la requête
        $stmt->bindParam(':terme', $terme, PDO::PARAM_STR);
        $stmt->bindParam(':contexte', $contexte, PDO::PARAM_STR);
        $stmt->bindParam(':exemples', $exemples, PDO::PARAM_STR);

        // PDO::PARAM_STR définit le type string à chaque paramètre fourni
        // Exécution de la requête préparée
        $stmt->execute();
    }

    echo "Les termes ont été insérés avec succès dans la base de données.";
} catch (PDOException $e) {
    echo "Erreur lors de la connexion ou de l'insertion : " . $e->getMessage();
}
?>
<?php

$host = 'localhost';
$dbname = 'termes_jeunes_db';
$username = 'hugo';
$password = 'Hugo290210120510!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des termes
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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Ajouter une nouvelle traduction du turfu</title>
</head>
<body>
    <h1>Ajouter une nouvelle traduction du turfu</h1>
    <form action="app/ajouter_traduction.php" method="post">
        <label for="terme">Terme :</label>
        <input type="text" id="terme" name="terme" required>

        <label for="contexte">Contexte :</label>
        <textarea id="contexte" name="contexte" required></textarea>

        <label for="exemples">Exemples :</label>
        <textarea id="exemples" name="exemples" required></textarea>

        <button type="submit" class="button">Ajouter</button>
    </form>
    
    <br><br><br>

    <form method="post">
        <label for="terme">Sélectionnez un terme :</label>
        <select id="terme" name="terme" onchange="this.form.submit()">
            <option value="">-- Sélectionner un terme --</option>
            <?php foreach ($termes as $terme): ?>
                <option value="<?= $terme['id'] ?>" <?= isset($termeId) && $termeId == $terme['id'] ? 'selected' : '' ?>><?= htmlspecialchars($terme['terme']) ?></option>
            <?php endforeach; ?>
        </select>
    </form>

    <div id="contexte-container" style="margin-top: 20px;">
        <h2>Contexte</h2>
        <p id="contexte"><?= $contexte ?></p>
    </div>
</body>
</html>


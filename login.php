<?php
require_once "config.php";

// Vérifie si le formulaire de connexion a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupère les informations du formulaire
    $login = $_POST['login'];
    $mot_de_passe = $_POST['mot_de_passe'];

    // Prépare une requête sécurisée avec des paramètres liés
    $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE email = ? OR nom = ?");
    $stmt->bind_param("ss", $login, $login); // "ss" signifie deux paramètres de type string
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    // Vérifie le mot de passe et redirige si les informations sont correctes
    if ($result && password_verify($mot_de_passe ,$result["mot_de_passe"])) {
        session_start();
        $_SESSION['idUser'] = $result["id_utilisateur"]; // Identifiant de l'utilisateur
        header("Location: index.php"); // Redirige vers la page admin
        exit();
    } else {
        $erreur = "Identifiants invalides.";
    }

    // Ferme la requête préparée
    $stmt->close();
}

// Ferme la connexion
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    <link rel="stylesheet" href="style.css"> <!-- Lien vers le fichier CSS -->
</head>
<body>
    <div class="login-container">
        <h1>Connexion Admin</h1>
        <?php if (isset($erreur)): ?>
            <p class="error"><?php echo $erreur; ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <label for="login">Login: </label>
            <input type="text" name="login" placeholder="votre login ou email@example.com" required>
            <label for="mot_de_passe">Mot de passe: </label>
            <input type="password" name="mot_de_passe" required>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>

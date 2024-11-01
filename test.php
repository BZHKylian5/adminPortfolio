<?php
require_once "config.php";

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['idUser'])) {
    header("Location: login.php"); // Redirige vers la page de connexion
    exit(); // Assure que le script s'arrête après la redirection
}

$idUser = $_SESSION['idUser']; // Assurez-vous que vous récupérez l'ID utilisateur de la session

$stmt = $conn->prepare("SELECT * from utilisateur WHERE id_utilisateur = ?");
$stmt->bind_param("i", $idUser);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt = $conn->prepare("SELECT *
                         FROM photo_profil pp
                         LEFT JOIN image i ON i.id_image = pp.id_id_image
                         WHERE pp.id_utilisateur = ?");
if ($stmt === false) {
    die("Erreur de préparation de la requête : " . $conn->error); // Affiche l'erreur si la préparation échoue
}

// Lier le paramètre
$stmt->bind_param("i", $idUser);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();
print_r($profile)
?>
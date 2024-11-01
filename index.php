<?php
require_once "config.php";


if (!isset($_SESSION['idUser'])) {
    header("Location: login.php"); // Redirige vers la page de connexion
    exit(); // Assure que le script s'arrête après la redirection
}

$idUser = $_SESSION['idUser']; // Récupère l'ID utilisateur de la session

// Récupère les informations de l'utilisateur
$stmt = $conn->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = ?");
$stmt->bind_param("i", $idUser);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Récupère l'URL de la photo de profil
$stmt = $conn->prepare("SELECT count(*) as nbphoto FROM photo_projet");
if ($stmt === false) {
    die("Erreur de préparation de la requête : " . $conn->error); // Affiche l'erreur si la préparation échoue
}

$stmt->execute();
$result = $stmt->get_result();
$photo = $result->fetch_assoc();


$stmt = $conn->prepare("SELECT count(*) as nbprojet FROM projet");
if ($stmt === false) {
    die("Erreur de préparation de la requête : " . $conn->error); // Affiche l'erreur si la préparation échoue
}

$stmt->execute();
$result = $stmt->get_result();
$photoprojet = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>
<body>
    <?php require_once "./components/header.php";?>
    <main>
        <section class="dashboard">
            <h2>Résumé des Statistiques</h2>
            <div class="stats">
                <div class="stat">
                    <h3>Total Projets</h3>
                    <p><?php echo $photoprojet['nbprojet']?></p>
                </div>
                
                <div class="stat">
                    <h3>Total Images</h3>
                    <p><?php echo $photo['nbphoto']; ?></p>
                </div>
            </div>
        </section>

        <section class="project-management">
            <h2>Gestion des Projets</h2>
            <button class="btn">Ajouter un Projet</button>
            <input type="text" placeholder="Rechercher un projet..." class="search">
            <div class="swiper mySwiper">
                
            <div class="swiper-wrapper">
                <?php 

                $stmt = $conn->prepare("SELECT * FROM projet");
                if ($stmt === false) {
                    die("Erreur de préparation de la requête : " . $conn->error); // Affiche l'erreur si la préparation échoue
                }

                $stmt->execute();
                $result = $stmt->get_result();
                $photoprojet = $result->fetch_assoc();

                foreach($projets as $projet) {
                    // Fetch images for each project
                    $stmt = $conn->prepare("SELECT * FROM photo_projet pp LEFT JOIN photo p ON pp.id_image = p.id_image WHERE pp.id_projet = ?");
                    if ($stmt === false) {
                        die("Erreur de préparation de la requête : " . $conn->error);
                    }

                    $stmt->bind_param("i", $projet['id_projet']); // Bind the project ID
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $imagesprojet = $result->fetch_all(MYSQLI_ASSOC); // Fetch all images for the project

                    ?>
                    <div class="swiper-slide">
                        <img src="<?php echo htmlspecialchars($imagesprojet[0]['url']); ?>" alt="Image du projet" /> <!-- Use the actual URL field here -->
                        <div>
                            <p><?php echo htmlspecialchars($projet['description']); ?></p> <!-- Assuming there's a description field -->
                        </div>
                    </div>
                    <?php
                    }
                ?>
            </div>
                <div class="swiper-pagination"></div>
            </div>
        </section>

        <section class="user-management">
            <h2>Gestion des Utilisateurs</h2>
            <button class="btn">Ajouter un Utilisateur</button>
            <ul class="user-list">
                <li>Utilisateur 1</li>
                <li>Utilisateur 2</li>
            </ul>
        </section>

        <section class="upload-images">
            <h2>Télécharger des Images</h2>
            <form action="#" method="post" enctype="multipart/form-data">
                <input type="file" name="image" required>
                <button type="submit" class="btn">Télécharger</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Kylian Houedec. Tous droits réservés.</p>
    </footer>
      <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
  var swiper = new Swiper(".mySwiper", {
    pagination: {
      el: ".swiper-pagination",
      dynamicBullets: true,
    },
  });
</script>
</body>
</html>

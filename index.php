<?php
require_once "config.php";

session_start(); // Start the session

if (!isset($_SESSION['idUser'])) {
    header("Location: login.php"); // Redirect to login page
    exit(); // Ensure the script stops after redirection
}

$idUser = $_SESSION['idUser']; // Get user ID from session

function fetchSingleValue($conn, $query, $param) {
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $param);
    if (!$stmt->execute()) {
        // Handle error
        echo "Error: " . $stmt->error;
        return null;
    }
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Retrieve user information
$user = fetchSingleValue($conn, "SELECT * FROM utilisateur WHERE id_utilisateur = ?", $idUser);

// Retrieve total number of photos
$photo = fetchSingleValue($conn, "SELECT COUNT(*) AS nbphoto FROM photo_projet", null);

// Retrieve total number of projects
$photoprojet = fetchSingleValue($conn, "SELECT COUNT(*) AS nbprojet FROM projet", null);

// Retrieve all projects
$stmt = $conn->prepare("SELECT * FROM projet");
$stmt->execute();
$result = $stmt->get_result();
$projets = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Link to CSS file -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>
<body>
    <?php require_once "./components/header.php"; ?>
    <main>
        <section class="dashboard">
            <h2>Résumé des Statistiques</h2>
            <div class="stats">
                <div class="stat">
                    <h3>Total Projets</h3>
                    <p><?php echo htmlspecialchars($photoprojet['nbprojet']); ?></p>
                </div>
                <div class="stat">
                    <h3>Total Images</h3>
                    <p><?php echo htmlspecialchars($photo['nbphoto']); ?></p>
                </div>
            </div>
        </section>

        <section class="project-management">
            <h2>Gestion des Projets</h2>
            <button class="btn">Ajouter un Projet</button>
            <input type="text" placeholder="Rechercher un projet..." class="search">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                <?php 
                foreach ($projets as $projet) {
                    // Fetch images for each project
                    $stmt = $conn->prepare("SELECT * FROM photo_projet pp LEFT JOIN image i ON pp.id_image = i.id_image WHERE pp.id_projet = ?");
                    $stmt->bind_param("i", $projet['id_projet']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $imagesprojet = $result->fetch_all(MYSQLI_ASSOC);

                    $imageUrl = !empty($imagesprojet) ? htmlspecialchars($imagesprojet[0]['url']) : 'fallback_image_url.jpg'; // Set a fallback image
                    ?>
                    <div class="swiper-slide">
                        <div class="slide-background" style="background-image: url('<?php echo $imageUrl; ?>');"></div>
                        <div class="description-banner">
                            <p><?php echo htmlspecialchars($projet['description']); ?></p>
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
            <?php
                // Retrieve all users
                $stmt = $conn->prepare("SELECT * FROM utilisateur");
                $stmt->execute();
                $result = $stmt->get_result();
                while ($user = $result->fetch_assoc()) {
                    echo "<li>" . htmlspecialchars($user['username']) . " <button>Edit</button> <button>Delete</button></li>"; // Include action buttons
                }
            ?>
            </ul>
        </section>

        <section class="upload-images">
            <h2>Télécharger des Images</h2>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <input type="file" name="image" accept="image/*" required>
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
        var swiper = new Swiper(".swiper-container", {
            pagination: {
                el: ".swiper-pagination",
                dynamicBullets: true,
            },
        });
    </script>
</body>
</html>

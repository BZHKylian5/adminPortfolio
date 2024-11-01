    <?php
    require_once "config.php";

    session_start(); // Start the session

    if (!isset($_SESSION['idUser'])) {
        header("Location: login.php"); // Redirect to login page
        exit(); // Ensure the script stops after redirection
    }

    $idUser = $_SESSION['idUser']; // Get user ID from session

    // Retrieve user information
    $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = ?");
    $stmt->bind_param("i", $idUser);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Retrieve total number of photos
    $stmt = $conn->prepare("SELECT COUNT(*) AS nbphoto FROM photo_projet");
    if ($stmt === false) {
        die("Error preparing query: " . $conn->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $photo = $result->fetch_assoc();

    // Retrieve total number of projects
    $stmt = $conn->prepare("SELECT COUNT(*) AS nbprojet FROM projet");
    if ($stmt === false) {
        die("Error preparing query: " . $conn->error);
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
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        <?php 
                        // Fetch all projects
                        $stmt = $conn->prepare("SELECT * FROM projet");
                        if ($stmt === false) {
                            die("Error preparing query: " . $conn->error);
                        }
                        
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $projets = $result->fetch_all(MYSQLI_ASSOC); // Fetch all projects

                        foreach ($projets as $projet) {
                            // Fetch images for each project
                            $stmt = $conn->prepare("SELECT * FROM photo_projet pp LEFT JOIN image i ON pp.id_image = i.id_image WHERE pp.id_projet = ?");
                            if ($stmt === false) {
                                die("Error preparing query: " . $conn->error);
                            }

                            $stmt->bind_param("i", $projet['id_projet']); // Bind the project ID
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $imagesprojet = $result->fetch_all(MYSQLI_ASSOC); // Fetch all images for the project

                            // Check if there are images before accessing the first one
                            if (!empty($imagesprojet)) {
                                ?>
                                <div class="swiper-slide">
                                    <img src="<?php echo htmlspecialchars($imagesprojet[0]['url']); ?>" alt="Image du projet" /> <!-- Use the actual URL field here -->
                                    <div>
                                        <p><?php echo htmlspecialchars($projet['description']); ?></p> <!-- Assuming there's a description field -->
                                    </div>
                                </div>
                                <?php
                            } else {
                                // Handle cases where there are no images
                                ?>
                                <div class="swiper-slide">
                                    <div class="slide-background" style="background-image: url('<?php echo htmlspecialchars($imagesprojet[0]['url']); ?>');">
                                        <div class="description-banner">
                                            <p><?php echo htmlspecialchars($projet['description']); ?></p>
                                        </div>
                                    </div>
                                </div>

                                <?php
                            }
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

<?php
require_once "config.php";
// Vérifie si l'utilisateur est connecté

if (!isset($_SESSION['idUser'])) {
    header("Location: login.php"); // Redirige vers la page de connexion
    exit(); // Assure que le script s'arrête après la redirection
}

$stmt = $conn -> prepare("SELECT * from utilisateur WHERE id_utilisateur = '$idUser'");
$stmt -> execute();
$result = $stmt -> get_result();
$user = $result -> fetch_assoc();



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Lien vers le fichier CSS -->
</head>
<body>
    <header>    
        <img src="./asset/img/logo/logo_KH_Admin.png" alt="logo du site web un K et un H imbriquer">
        <?php
            if($isLoggedIn){
                ?>
                <img id="profilePic" src="<?php echo $profile["url"] ?>" title="Photo de profil utilisateur">

                <!-- Menu caché intégré dans le header -->
                <div id="profileMenu" class="hidden">
                    <span id="backButton">< Retour</span>
                    <figure id="imagProfil">
                        <img src="<?php echo $profile["url"] ?>" title="photo de profil utilisateur" id="menuProfilePic">
                        <figcaption>
                            <?php

                            ?>
                        </figcaption>
                    </figure>
                    <ul>
                        <li><a href="">Accueil</a></li>
                        <li><a href="">Créer un projet</a></li>
                        <li><a href="">Modifier un projet</a></li>
                    </ul>
                    <div>
                        <a id="logoutButton" class="buttonMenu"  href="logout.php">Déconnexion</a>
                    </div>
                </div>

            <?php
        } else {
            ?>
            <a href="../login.php"><div id="btnConn">Connexion</div></a>
        <?php
        }
        ?>
    </div>
        
    </header>

    <main>
        <section class="dashboard">
            <h2>Résumé des Statistiques</h2>
            <div class="stats">
                <div class="stat">
                    <h3>Total Projets</h3>
                    <p>10</p>
                </div>
                <div class="stat">
                    <h3>Total Utilisateurs</h3>
                    <p>5</p>
                </div>
                <div class="stat">
                    <h3>Total Images</h3>
                    <p>20</p>
                </div>
            </div>
        </section>

        <section class="project-management">
            <h2>Gestion des Projets</h2>
            <button class="btn">Ajouter un Projet</button>
            <input type="text" placeholder="Rechercher un projet..." class="search">
            <ul class="project-list">
                <li>Projet 1</li>
                <li>Projet 2</li>
                <li>Projet 3</li>
            </ul>
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
</body>
</html>

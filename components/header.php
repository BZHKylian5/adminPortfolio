<?php
require_once "config.php";


// Récupère les informations de l'utilisateur
$stmt = $conn->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = ?");
$stmt->bind_param("i", $idUser);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Récupère l'URL de la photo de profil
$stmt = $conn->prepare("SELECT pp.id_utilisateur, i.url 
                         FROM photo_profil pp
                         LEFT JOIN image i ON i.id_image = pp.id_image
                         WHERE pp.id_utilisateur = ?");
if ($stmt === false) {
    die("Erreur de préparation de la requête : " . $conn->error); // Affiche l'erreur si la préparation échoue
}

$stmt->bind_param("i", $idUser);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();
?>
<header>    
    <img src="./asset/img/logo/logo_KH_Admin.png" alt="logo du site web un K et un H imbriquer">
    <?php
        if (isset($user)) { // Vérifiez si l'utilisateur est défini
            ?>
            <img id="profilePic" src="<?php echo htmlspecialchars($profile["url"]) ?>" title="Photo de profil utilisateur">
            <div id="profileMenu" class="hidden">
                <span id="backButton">< Retour</span>
                <figure id="imagProfil">
                    <img src="<?php echo htmlspecialchars($profile["url"]) ?>" title="photo de profil utilisateur" id="menuProfilePic">
                    <figcaption>
                        <?php echo htmlspecialchars($user["nom"]) ?>
                    </figcaption>
                </figure>
                <ul>
                    <li><a href="">Accueil</a></li>
                    <li><a href="">Créer un projet</a></li>
                    <li><a href="">Modifier un projet</a></li>
                </ul>
                <div>
                    <a id="logoutButton" class="buttonMenu" href="logout.php">Déconnexion</a>
                </div>
            </div>
        <?php
        } else {
            ?>
            <a href="../login.php"><div id="btnConn">Connexion</div></a>
        <?php
        }
        ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const profilePic = document.getElementById("profilePic");
            const profileMenu = document.getElementById("profileMenu");
            const backButton = document.getElementById("backButton");

            // Fonction pour afficher/cacher le menu
            function toggleMenu() {
                if (profileMenu.classList.contains("show")) {
                    profileMenu.classList.remove("show");
                    profileMenu.classList.add("hide");

                    // Retirer la classe "hide" après la transition
                    setTimeout(() => {
                        profileMenu.classList.remove("hide");
                    }, 300); // Temps de la transition en ms
                } else {
                    profileMenu.classList.remove("hide");
                    profileMenu.classList.add("show");
                }
            }

            // Écouteur pour afficher le menu au clic sur l'image de profil
            if (profilePic) {
                profilePic.addEventListener("click", toggleMenu);
            }

            // Écouteur pour fermer le menu au clic sur le bouton "Retour"
            if (backButton) {
                backButton.addEventListener("click", toggleMenu);
            }

            // Écouteur pour fermer le menu en cliquant en dehors
            document.addEventListener("click", function(event) {
                if (!profileMenu.contains(event.target) && !profilePic.contains(event.target)) {
                    if (profileMenu.classList.contains("show")) {
                        toggleMenu();
                    }
                }
            });
        });
    </script>
</header>
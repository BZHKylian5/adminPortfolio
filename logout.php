<?php
require_once "config.php";

if($isLoggedIn){
    $stmt = $conn -> prepare("UPDATE utilisateur SET dernier_login = CURRENT_TIMESTAMP() WHERE id_utilisateur = '$idUser'");
    $stmt -> execute();
    $_SESSION = [];
    session_destroy();
}

header("location: index.php");
exit();
?>
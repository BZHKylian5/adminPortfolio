<?php
require_once "config.php";

if($isLoggedIn){
    $_SESSION = [];
    session_destroy();
}

header("location: index.php");
exit();
?>
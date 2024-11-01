<?php
include('./SECURE/config.php');

// Connexion avec MySQLi
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zeiterfassung";

// Verbindung zur Datenbank herstellen
$conn = new mysqli($servername, $username, $password, $dbname);

// Verbindung prüfen
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// UTF-8 Zeichenkodierung setzen
$conn->set_charset("utf8");
?>

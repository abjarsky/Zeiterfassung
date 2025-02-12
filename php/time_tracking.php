<?php
include '../php/db_config.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Nicht autorisiert. Bitte <a href='../html/login.html'>einloggen</a>.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $start_time = $_POST["start_time"];
    $end_time = $_POST["end_time"];
    $start_location = $_POST["start_location"];
    $end_location = $_POST["end_location"];

    $stmt = $conn->prepare("INSERT INTO time_tracking (user_id, start_time, end_time, start_location, end_location) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $start_time, $end_time, $start_location, $end_location);
    
    if ($stmt->execute()) {
        echo "Zeiterfassung erfolgreich gespeichert.";
    } else {
        echo "Fehler bei der Speicherung: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>
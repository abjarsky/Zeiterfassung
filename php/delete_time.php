<?php
include '../php/db_config.php';
session_start();

$data = json_decode(file_get_contents("php://input"), true);
$id = $data["id"];

// Eintrag aus der Datenbank löschen
$stmt = $conn->prepare("DELETE FROM time_tracking WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Eintrag erfolgreich gelöscht.";
} else {
    echo "Fehler: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

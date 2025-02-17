<?php
include '../php/db_config.php';
session_start();

$data = json_decode(file_get_contents("php://input"), true);
$id = $data["id"];
$field = $data["field"];
$newValue = $data["newValue"];

// Sicherheit: Nur erlaubte Felder bearbeiten
$allowed_fields = ["customer_firstname", "customer_lastname", "customer_address", "customer_zip", "customer_city", "employee_name", "work_date", "start_time", "end_time", "break_time", "total_time"];
if (!in_array($field, $allowed_fields)) {
    die("Fehler: Ungültiges Feld.");
}

$query = "UPDATE time_tracking SET `$field` = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $newValue, $id);

if ($stmt->execute()) {
    echo "Änderungen gespeichert.";
} else {
    echo "Fehler: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

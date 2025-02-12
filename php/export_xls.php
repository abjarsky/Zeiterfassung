<?php
include '../php/db_config.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Nicht autorisiert. Bitte <a href='../html/login.html'>einloggen</a>.");
}

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=zeiterfassung.xls");

echo "Mitarbeiter\tStartzeit\tEndzeit\tKunde\tStart-Standort\tEnd-Standort\n";

if ($_SESSION["user_role"] === "admin") {
    $stmt = $conn->prepare("SELECT users.name, time_tracking.start_time, time_tracking.end_time, time_tracking.customer_name, time_tracking.start_location, time_tracking.end_location FROM time_tracking JOIN users ON time_tracking.user_id = users.id");
} else {
    $stmt = $conn->prepare("SELECT users.name, time_tracking.start_time, time_tracking.end_time, time_tracking.customer_name, time_tracking.start_location, time_tracking.end_location FROM time_tracking JOIN users ON time_tracking.user_id = users.id WHERE time_tracking.user_id = ?");
    $stmt->bind_param("i", $_SESSION["user_id"]);
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo implode("\t", $row) . "\n";
}

$stmt->close();
$conn->close();
?>
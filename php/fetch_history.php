<?php
include '../php/db_config.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Nicht autorisiert. Bitte <a href='../html/login.html'>einloggen</a>.");
}

header('Content-Type: application/json');

$user_id = $_SESSION["user_id"];

if ($_SESSION["user_role"] === "admin") {
    $stmt = $conn->prepare("SELECT * FROM time_tracking");
} else {
    $stmt = $conn->prepare("SELECT * FROM time_tracking WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($data);
?>

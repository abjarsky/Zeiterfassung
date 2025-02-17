<?php
include '../php/db_config.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die(json_encode(["status" => "error", "message" => "Nicht autorisiert. Bitte einloggen."]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $entry_id = $_POST["entry_id"] ?? null;
    $geo_lat = $_POST["geo_lat"] ?? null;
    $geo_lng = $_POST["geo_lng"] ?? null;
    $geo_address = $_POST["geo_address"] ?? null;

    if (!$entry_id || !$geo_lat || !$geo_lng || !$geo_address) {
        die(json_encode(["status" => "error", "message" => "Fehlende Daten."]));
    }

    // Speichert die Geodaten in der Datenbank
    $stmt = $conn->prepare("UPDATE time_tracking SET start_location = ?, geo_location = ? WHERE id = ? AND user_id = ?");
    $geo_location = $geo_lat . ", " . $geo_lng;
    $stmt->bind_param("ssii", $geo_address, $geo_location, $entry_id, $user_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Geodaten erfolgreich gespeichert."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Fehler beim Speichern der Geodaten: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>

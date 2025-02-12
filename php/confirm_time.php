<?php
include '../php/db_config.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Nicht autorisiert. Bitte <a href='../html/login.html'>einloggen</a>.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $customer_name = $_POST["customer_name"] ?? '';
    $customer_address = $_POST["customer_address"] ?? '';
    $employee_name = $_POST["employee_name"] ?? '';
    $start_time = $_POST["start_time"] ?? '';
    $end_time = $_POST["end_time"] ?? '';
    $break_time = $_POST["break_time"] ?? '0';
    $total_time = $_POST["total_time"] ?? '';
    $signature = $_POST["signature"] ?? '';

    // Prüfen, ob eine Unterschrift vorhanden ist
    if (!empty($signature)) {
        $signature_path = "../uploads/signature_" . time() . ".png";
        $signature_data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $signature));
        
        if ($signature_data) {
            file_put_contents($signature_path, $signature_data);
        } else {
            $signature_path = NULL;
        }
    } else {
        $signature_path = NULL;
    }

    // Eintrag in die Datenbank
    $stmt = $conn->prepare("INSERT INTO time_tracking (user_id, customer_name, customer_address, employee_name, start_time, end_time, break_time, total_time, signature) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssss", $user_id, $customer_name, $customer_address, $employee_name, $start_time, $end_time, $break_time, $total_time, $signature_path);
    
    if ($stmt->execute()) {
        echo "Zeiterfassungsdaten erfolgreich gespeichert.";
    } else {
        echo "Fehler bei der Speicherung: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>

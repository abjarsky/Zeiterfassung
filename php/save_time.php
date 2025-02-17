<?php
include '../php/db_config.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    die("Nicht autorisiert. Bitte <a href='../html/login.html'>einloggen</a>.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    
    // Kundendaten
    $customer_firstname = $_POST["customer_firstname"] ?? '';
    $customer_lastname = $_POST["customer_lastname"] ?? '';
    $customer_address = $_POST["customer_address"] ?? '';
    $customer_zip = $_POST["customer_zip"] ?? '';
    $customer_city = $_POST["customer_city"] ?? '';

    // Mitarbeiterdaten
    $employee_name = $_POST["employee_name"] ?? '';

    // Arbeitszeiten
    $work_date = $_POST["work_date"] ?? ''; // Arbeitsdatum
    $start_time = $_POST["start_time"] ?? '';
    $end_time = $_POST["end_time"] ?? '';
    $break_time = $_POST["break_time"] ?? '0';
    $total_time = $_POST["total_time"] ?? '';

    // Standortinformationen
    $geo_location = $_POST["geo_location"] ?? '';

    // Kundenzusätzliche Infos
    $customer_info = $_POST["customer_info"] ?? '';

    // Unterschrift speichern
    if (!empty($_POST["signature"])) {
        $signature_path = "../uploads/signature_" . time() . ".png";
        $signature_data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST["signature"]));
        
        if ($signature_data) {
            file_put_contents($signature_path, $signature_data);
        } else {
            $signature_path = NULL;
        }
    } else {
        $signature_path = NULL;
    }
// ✨ 🟢 **Kombiniere Datum & Zeit für Start & Ende**
    $start_datetime = $work_date . " " . $start_time;
    $end_datetime = $work_date . " " . $end_time;
    // SQL-Query für Eintrag in die `time_tracking`-Tabelle
    $stmt = $conn->prepare("
        INSERT INTO time_tracking 
        (user_id, customer_firstname, customer_lastname, customer_address, customer_zip, customer_city, 
        employee_name, work_date, start_time, end_time, break_time, total_time, geo_location, customer_info, signature) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    // Richtige Anzahl an Parametern übergeben
    $stmt->bind_param("issssssssssssss", 
        $user_id, $customer_firstname, $customer_lastname, $customer_address, 
        $customer_zip, $customer_city, $employee_name, $work_date, $start_time, 
        $end_time, $break_time, $total_time, $geo_location, $customer_info, $signature_path
    );
    
    if ($stmt->execute()) {
        // Erfolgreich gespeichert -> Weiterleitung zur Verlaufsseite
        header("Location: ../html/history.html");
        exit();
    } else {
        echo "Fehler bei der Speicherung: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>

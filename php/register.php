<?php
include '../php/db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($password !== $confirm_password) {
        die("Fehler: Die Passwörter stimmen nicht überein.");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'mitarbeiter')");
    $stmt->bind_param("sss", $name, $email, $hashed_password);
    
    if ($stmt->execute()) {
        echo "Registrierung erfolgreich. <a href='../html/login.html'>Jetzt einloggen</a>";
    } else {
        echo "Fehler bei der Registrierung: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>

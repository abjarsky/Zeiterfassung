<?php
include '../php/db_config.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Benutzer aus der Datenbank abrufen
    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed_password, $role);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["user_name"] = $name;
            $_SESSION["user_role"] = $role;

            // Weiterleitung abhängig von der Benutzerrolle
            if ($role === 'admin') {
                header("Location: ../html/dashboard.html");
            } else {
                header("Location: ../html/time_tracking.html");
            }
            exit();
        } else {
            echo "<script>alert('Falsches Passwort.'); window.location.href='../html/index.html';</script>";
        }
    } else {
        echo "<script>alert('E-Mail nicht gefunden.'); window.location.href='../html/index.html';</script>";
    }
    
    $stmt->close();
    $conn->close();
}
?>

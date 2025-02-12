<?php
include '../php/db_config.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

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
            
            echo "Erfolgreich eingeloggt. <a href='../html/dashboard.html'>Zum Dashboard</a>";
        } else {
            echo "Falsches Passwort.";
        }
    } else {
        echo "E-Mail nicht gefunden.";
    }
    
    $stmt->close();
    $conn->close();
}
?>
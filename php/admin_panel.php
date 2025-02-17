<?php
include '../php/db_config.php';
session_start();

// Überprüfung, ob der Nutzer Admin ist
if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== "admin") {
    die("Nicht autorisiert. Bitte <a href='../html/login.html'>einloggen</a>.");
}

// Eintrag löschen
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_entry'])) {
    $entry_id = $_POST['entry_id'];
    $stmt = $conn->prepare("DELETE FROM time_tracking WHERE id = ?");
    $stmt->bind_param("i", $entry_id);

    if ($stmt->execute()) {
        echo "<script>alert('Eintrag erfolgreich gelöscht.'); window.location.href='admin_panel.php';</script>";
    } else {
        echo "<script>alert('Fehler beim Löschen: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Einträge abrufen
$result = $conn->query("SELECT time_tracking.id, users.name, time_tracking.start_time, time_tracking.end_time, 
                        time_tracking.customer_name, time_tracking.start_location, time_tracking.end_location
                        FROM time_tracking 
                        JOIN users ON time_tracking.user_id = users.id");
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Zeiterfassung</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <h1>Admin Panel - Zeiterfassung</h1>
        <nav>
            <ul>
                <li><a href="../html/dashboard.html">Dashboard</a></li>
                <li><a href="../html/history.html">Verlauf</a></li>
                <li><a href="../html/export.html">Datenexport</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <section>
            <h2>Erfasste Arbeitszeiten verwalten</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mitarbeiter</th>
                        <th>Startzeit</th>
                        <th>Endzeit</th>
                        <th>Kunde</th>
                        <th>Start-Standort</th>
                        <th>End-Standort</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['start_time']; ?></td>
                            <td><?php echo $row['end_time']; ?></td>
                            <td><?php echo $row['customer_name']; ?></td>
                            <td><?php echo $row['start_location']; ?></td>
                            <td><?php echo $row['end_location']; ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="entry_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="delete_entry">Löschen</button>
                                </form>
                                <a href="edit_entry.php?id=<?php echo $row['id']; ?>">Bearbeiten</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>
    
    <footer>
        <p>&copy; 2025 Koria KFZ-Gutachter - Zeiterfassungssystem</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>

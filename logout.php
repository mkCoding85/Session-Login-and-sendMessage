<?php
// [LOGOUT FUNCTION] //
session_start();

if (isset($_SESSION["id"])) {

    // Datenbankverbindung //
    include('dbconnect.php');
 
    $logoutID = htmlspecialchars($_GET['id']);

    if (isset($logoutID)) {
        // Statsu Offline setzen //
        $status = "Jetzt Offline";
        $logoutSQL = $conn->query("UPDATE users SET status = '$status' WHERE id = {$_GET['id']}");
        // Prüfe den Logout //
        if ($logoutSQL) {
            unset($_SESSION['id']);
            session_destroy();
            setcookie('PHPSESSID', '', time()-3600, '/', 0, 0, 0); // Zerstöre die Cookie-Session. //
            header("Location: index.php");
            exit();
        }
    }
}

// Close Tag //
?>
<?php 
session_start();
include 'dbconnect.php';
// TODO: Der Benutzer soll sich mit Email & Password anmelden. //
if (isset($_POST['loginSubmit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    // Prüfe Eingabefelder ob diese leer sind oder nicht. //
    if (empty($email) && empty($password)) {
        header("Location: index.php?empty");
        exit();
    } else {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);
        $resultCheck = mysqli_num_rows($result);
        if ($resultCheck < 1) {
            header("Location: index.php?error");
            exit();
        } else {
            if ($zeile = mysqli_fetch_assoc($result)) {
                // De-hashing the password //
                $hashedPwdCheck = password_verify($password, $zeile['password']);
                if ($hashedPwdCheck == false) {
                    header("Location: index.php?error");
                    exit();
                } elseif ($hashedPwdCheck == true) {

                     // Prüfen ob alles in der Session vorhanden ist //
                    $status = "Jetzt Online";
                    $updateSQL = $conn->query("UPDATE users SET status = '$status' WHERE email = '$email'");

                    // Login the users //
                    $_SESSION['id'] = $zeile['id'];
                    $_SESSION['username'] = $zeile['vorname']."\n".$zeile['nachname'];
                    header("Location: index.php");
                    exit();

                }
            }
        }
    }
}
// Close //
?>
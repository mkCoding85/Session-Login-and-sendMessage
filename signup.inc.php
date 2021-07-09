<?php
// Wenn Button registration geklickt wurde. //
if (isset($_POST['registrieren'])) {
    
    // Signup //
    include("dbconnect.php");

    // Eintrag in die Datenbank: profileimg //
    $vorname = mysqli_real_escape_string($conn, $_POST['vorname']);
    $nachname = mysqli_real_escape_string($conn, $_POST['nachname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (empty($_POST['vorname']) && empty($_POST['nachname']) && empty($_POST['email']) && empty($_POST['password'])) {
        header("Location: signup.php?registration=empty");
        exit();
    } else {
        // Check if input characters are valid //
        if (!preg_match("/^[a-zA-Z]*$/", $vorname) && !preg_match("/^[a-zA-Z]*$/", $nachname) ) {
            header("Location: signup.php?registration=invalid");
            exit(); 
        } else { 
            // Check if email is valid //
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                header("Location: signup.php?registration=email");
                exit();
            } else {
                // Prüfe ob Email bereits existiert //
                $sql = "SELECT * FROM users WHERE email='$email'";
                $result = mysqli_query($conn, $sql);
                $resultCheck = mysqli_num_rows($result);
                // Wenn Datensatz größer als 0 ist. //
                if ($resultCheck > 0) {
                    header("Location: signup.php?registration=already");
                    exit();
                } else {

                    $status = "Jetzt Offline";

                    // Hashing the password //
                    $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

                    // Insert the Record
                    $sql = "INSERT INTO users (vorname, nachname, email, password, avatar, status) VALUES ('$vorname', '$nachname', '$email', '$hashedPwd', 1, '$status')";
                    mysqli_query($conn, $sql);
                    header("Location: signup.php?registration=success");
                    exit();

                } // END ELSE.
            }
        }
    }
} else {
    header("Location: signup.php");
    exit();
}
// Close //
?>
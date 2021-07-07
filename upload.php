<?php
// Session starten //
session_start();

// Datenbankverbindung //
include('dbconnect.php');

// Session-ID ermitteln //
$id = $_SESSION['id'];

// Wenn Submit geklickt wurde //
if (isset($_POST['submit'])) {
    $file = $_FILES['file'];
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));
    $allowed = array('jpg', 'jpeg', 'png', 'pdf');
    // ----------------------------------- //
    if (in_array($fileActualExt, $allowed)) {
        // Typ: Interger: *gleich === 0 //
        if ($fileError === 0) {
            if ($fileSize < 10000000) {
                // Avatar Filename //
                $fileNameNew = "profile".$id.".".$fileActualExt;
                $fileDestination = 'uploads/'.$fileNameNew;
                move_uploaded_file($fileTmpName, $fileDestination);
                // SQL Anweisung //
                $sql = "UPDATE users SET avatar = 0 WHERE id = '$id'";
                $result = $conn->query($sql);
                $_SESSION['success'] = "<p class='ok'>Profilbild erfolgreich hochladen. <i class='fas fa-times'></i></p>";
                // Redirect: editprofile.php //
                echo "<script>window.open('editprofile.php?id=".$id."&uploadsuccess','_self')</script>";
            } else {
                $_SESSION['success'] = "<p class='error'>Your file is too big! <i class='fas fa-times'></i></p>";
            }
        } else {
            $_SESSION['success'] = "<p class='error'>There was an error uploading file! <i class='fas fa-times'></i></p>";
        }
    } else {
        $_SESSION['success'] = "<p class='error'>You cannot upload files of the type! <i class='fas fa-times'></i></p>";
    }
}
// Close //
?>
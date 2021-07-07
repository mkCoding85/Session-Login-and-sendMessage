<?php
// Session starten //
session_start();

// Datenbankverbindung //
include('dbconnect.php');

// Session-ID ermitteln //
$id = $_SESSION['id'];

// SQL Statement //
$sql = "UPDATE users SET avatar = 1 WHERE id = '$id'";
$result = $conn->query($sql);

// Declare Variables for Avatar Image //
$filename = "uploads/profile".$id."*";
$fileinfo = glob($filename);
$fileext = explode(".", $fileinfo[0]);
$fileactualext = $fileext[1];
$file = "uploads/profile".$id.".".$fileactualext;

// Lösche alte Profilbild //
if (!unlink($file)) {
    $_SESSION['success'] = "<p class='ok'>Ihr Profilbid konnte nicht gelöscht werden. <i class='fas fa-times'></i></p>";
    echo "<script>window.open('editprofile.php?id=".$id."','_self')</script>";
} else {
    $_SESSION['success'] = "<p class='ok'>Ihr Profilbild wurde erfolgreich gelöscht. <i class='fas fa-times'></i></p>";
    echo "<script>window.open('editprofile.php?id=".$id."','_self')</script>";
}
// Close //
?>
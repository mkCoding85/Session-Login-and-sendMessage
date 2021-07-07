<?php
// Session starten //
session_start();
// Error Reporting //
error_reporting(0);
// Datenbankverbindung //
include('dbconnect.php');
// Functions //
include('functions.php');
// Close //
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css"/> 
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="cd-layout--wrapper">

        <?php 
            /* [ÄNDERN DER MESSAGE DATEN] */
            $id = $_POST['id'];
            $user_id = $_POST['user_id'];
            $timestamp = time();
            $datum = date("d.m.Y - H:i:s", $timestamp);
            $message = $_POST['message'];
            // Formular anzeigen //
            echo "<form method='POST' action='".editPosting($conn)."'>
                <input type='hidden' name='id' value='".$_POST['id']."'><br>
                <input type='hidden' name='user_id' value='".$_POST['user_id']."'><br>
                <input type='hidden' name='datum' value='".$datum."'><br>
                <textarea name='message'>".$message."</textarea><br>
                <button type='submit' class='btn' name='editSubmit'>Edit</button>
            </form>";
        /* [END PHP FUNCTION] */
        ?><br>
        <a href="index.php">Zurück</a>

    </div>
    
</body>
</html>
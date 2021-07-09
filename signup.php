<?php
date_default_timezone_set('Europe/Berlin');
include 'dbconnect.php';
// Close //
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Login</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css"/> 
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="cd-layout--wrapper">


    <?php // signup.php //
        $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];	
    ?>
    <h2>Registrieren</h2>
    <br>
    <form action="signup.inc.php" method="post" autocomplete="off">
        
        <label for="vorname">Vorname</label>
        <input type="text" name="vorname">
        <br>
        <label for="nachname">Nachname</label>
        <input type="text" name="nachname">
        <br>
        <label for="email">Email</label>
        <input type="email" name="email">
        <br>
        <label for="password">Password</label>
        <input type="password" name="password">

        <div>
            <button type="submit" name="registrieren">Signup</button>
        </div>

    </form>

    <?php 
        // Display Error Message using PHP 
        $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        // Wenn die eingabe nicht korrekt ist. //
        if (strpos($fullUrl, "registration=empty") == true) {
            echo "<p class='error'>Sie haben nicht alle Felder ausgefüllt</p>";
            exit();
        } elseif (strpos($fullUrl, "registration=invalid") == true) {
            echo "<p class='error'>Sie haben ungültige Zeichen verwendet!</p>";
            exit();
        } elseif (strpos($fullUrl, "registration=email") == true) {
            echo "<p class='error'>Sie haben eine ungültige E-Mail verwendet</p>";
            exit();
        } elseif (strpos($fullUrl, "registration=already") == true) {
            echo "<p class='error'>E-Mail Adresse ist bereits vergeben!</p>";
            exit();
        } elseif (strpos($fullUrl, "registration=success") == true) {
            echo "<p class='success'>Registrierung erfolgreich!</p>";
            exit();
        }
    // Close //
    ?>

</div>
    
</body>
</html>
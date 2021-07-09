<?php
// error_reporting(0);
date_default_timezone_set('Europe/Berlin');
// Datenbankverbindung //
include('dbconnect.php');
session_start();

// TODO: Ist User loggedin! //
if (isset($_SESSION['id'])) { 

    // TODO: Userdaten anzeigen //
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {

        $id = $_GET['id'];

        $sql = "SELECT * FROM users WHERE id = '$id'";
        $result = $conn->query($sql);
        $zeile = mysqli_fetch_assoc($result);
    }
}
// Close //
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css"/> 
    <link rel="stylesheet" href="style.css">
    <!-- jQuery Plugin -->
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
</head>
<body>
    
<div class="cd-layout--wrapper">

    <div class="infologin">
        <?php if (isset($_SESSION['success'])) : ?>
            <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']);
            ?>
        <?php endif; ?>
    </div>

    <div class="users infologin">
        <header>
            <div class="content">
                <?php 
                    // Ausgabe des Profilebild //
                    if ($zeile['avatar'] == 0) {
                        $filename = "uploads/profile".$zeile['id']."*";
                        $fileinfo = glob($filename);
                        $fileext = explode(".", $fileinfo[0]);
                        $fileactualext = $fileext[1];
                        echo "<img id='userimage' src='uploads/profile".$zeile['id'].".".$fileactualext."' alt='Avatar'>";
                    } else {
                        echo "<img id='userimage' src='uploads/profiledefault.jpg' alt='Avatar'>";
                    }
                    // Wenn status offline ist. //
                    ($zeile['status'] == "Jetzt Offline") ? $offline = "offline" : $offline = "";
                // Close //
                ?>
                <span class="status-dot ' <?php echo $offline; ?> '">
                    <i class="fas fa-circle"></i>
                </span>
                <div class="details">
                    <span>
                        <?php echo $zeile['vorname']."\n".$zeile['nachname']; ?>
                    </span>
                    <p class="status"><?php echo $zeile['status']; ?></p>
                </div>
            </div>
            <!-- End content -->
        </header>
        <!-- End header -->
        <!-- Avatar Upload & Delete -->
        <span class="cd-layout--user__container">
            <form action="upload.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="file" id="avatar" value="" style="display:none;">
                <button type="submit" name="submit" class="btn">Speichern</button>
            </form>
            <form action="deleteprofile.php" method="POST">
                <button type="submit" name="submit" class="btn">Löschen</button>
            </form>
        </span><br>
        <!-- End Avatar Upload & Delete -->
    </div>
    <!-- End users -->

    <!-- Userdaten bearbeiten -->
    <h3 style="font-size: 22px; color: #555; text-decoration: underline;">Userdaten aktualisieren</h3>
    <br>
    <form action="editprofile.php?id=<?php echo htmlspecialchars($zeile['id']); ?>" class="formular" method="post">
        <div class="clearfix">
            <label>Vorname:</label>
            <input type="text" name="vorname" id="vorname" value="<?php echo htmlspecialchars($zeile['vorname']); ?>">
        </div>
        <div class="clearfix">
            <label>Nachname:</label>
            <input type="text" name="nachname" id="nachname" value="<?php echo htmlspecialchars($zeile['nachname']); ?>">
        </div>
        <div class="clearfix">
            <label>Email Adresse:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($zeile['email']); ?>">
        </div>
        <div class="clearfix">
            <label>Password:</label>
            <input type="password" name="password" value="" placeholder="Password">
        </div>
        <div class="clearfix">
            <label>Password wiederholen:</label>
            <input type="password" name="passwordv" value="" placeholder="Type Password Again">
        </div><br>
        <div class="clearfix">
            <button type="submit" name="aktion" class="button" value="speichern">Aktualisieren</button>
        </div>
    </form><br>
    <p>
        <a href="index.php">zur Übersicht</a>
    </p>
    <?php // Edit Userdaten //
        if (isset($_POST['aktion']) && $_POST['aktion'] === 'speichern') {
            // Declare Variables //
            $vorname = htmlspecialchars($_POST['vorname']);
            $nachname = htmlspecialchars($_POST['nachname']);
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);
            // Wenn Password geändert wird. //
            $passwordCheck = '';
            // Ist Password gesetzt! //
            if ($_POST['password'] != '') {
                // wenn ja dann wiederhole password eingabe //
                if ($_POST['password'] == $_POST['passwordv']) {
                    // Hashing the password //
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $verify = true;
                    // Das Komma hier setzen //
                    $passwordCheck = ", password='$hashedPassword'";
                } else {
                    $verify = false;
                }
            } else {
                $verify = false;
            }
            // SQL Anweisung - Nach email kein komma setzen //
            $sql = "UPDATE users SET vorname='$vorname', nachname='$nachname', email='$email' $passwordCheck WHERE id='$id'";
            $result = $conn->query($sql);
            // TODO: Userdaten in der Datenbank aktualisieren //
            if($result) {
                $_SESSION['success'] = "<p class='ok'>Ihre Profildaten wurden aktualisiert. <i class='fas fa-times'></i></p>";
            } else {
                $_SESSION['success'] = "<p class='error'>Änderung hat nicht geklappt. <i class='fas fa-times'></i></p>";
            }
            // Redirect to editprofile.php //
            echo "<script>window.open('editprofile.php?id=".$zeile['id']."', '_self')</script>";
            exit();
        }
    // Close //
    ?>
</div>

</body>
<script type="text/javascript">
// TODO: Fehlermeldung //
window.onload = function() {
    let errors = document.getElementsByClassName('fa-times'); 
    for (let i = 0; i < errors.length; i++) { 
        errors[i].setAttribute('onclick','closeError(this);'); 
    } 
}
// TODO: ERROR / OK function //
function closeError(element) {
    let error = document.getElementsByClassName('error')[0]; 
    if (error !== undefined) {
        error.style.display = 'none'; 
    } 
    let ok = document.getElementsByClassName('ok')[0]; 
    if (ok !== undefined) {
        ok.style.display = 'none';
    } 
}
// ----------------------- //
// Avatar-Upload Function //
$(document).ready(function() {
    // Wenn der Benutzer auf die Schaltfläche zum Hochladen des Profilbilds klickt. //
    $(document).on('click', '#userimage', function() {
        $('#avatar').click();
        $(document).on('change', '#avatar', function() {
            // Nimm die Datei //
            let file = $('#avatar')[0].files[0];
            if (file) {
                let lesen = new FileReader();
                lesen.onload = function(event) {
                    $('#avatar').attr('value', file.name);
                    // Zeige das neue Bild //
                    $('#userimage').attr('src', event.target.result);
                };
                lesen.readAsDataURL(file);
            }
        });
    });
// End function //
});
</script>
</html>
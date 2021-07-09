<?php
date_default_timezone_set('Europe/Berlin');
include 'posting.inc.php';
include 'dbconnect.php';
session_start();
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
    
        <?php
        // Session ID ermitteln //
        if (isset($_SESSION['id'])) {
            echo "<p class='info'>Wilkommen <b style='color: darkred;'>".htmlspecialchars($_SESSION['username'])."</b></p>";
        ?><br>
        <form action="logout.php?id=<?php echo $_SESSION['id']; ?>" class="formular" method="post">
            <input type="submit" name="signup" value="Logout">
        </form>
        <?php } else {
            echo "<form action='login.inc.php' class='formular' method='POST'>
                <input type='email' name='email' placeholder='Email'>
                <input type='password' name='password' placeholder='Password'>
                <button type='submit' class='btn' name='loginSubmit'>Login</button>
            </form><br>";
            echo "<a href='signup.php'>Signup</a>";
            echo "<h4>Du bist nicht eingeloggt!</h4>";
        } 
        ?>

        <div style="display: block; padding: 10px 0;">
        
            <?php
                /* [STARTE SESSION ID] */
                if (isset($_SESSION['id'])) { 
                    /* [FUNCTION EINTRAG] */
                    echo "<form method='POST' action='".setPosting($conn)."'>
                            <input type='hidden' name='user_id' value='".$_SESSION['id']."'>
                            <textarea cols='33' rows='6' name='message' placeholder='Message'></textarea><br>
                            <button type='submit' class='btn' name='postSubmit'>Post</button>
                        </form>
                    <br>";
                }
            /* [END FUNCTION] */
            ?>
            
            <!-- [FUNCTION EINTRAG ANZEIGEN] --> 
            <?php getPosting($conn); ?>

        </div>


    </div>
    
</body>
</html>
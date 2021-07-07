<?php 

/* [DATEN EINTRAGEN] */
function setPosting($conn) {

    if (isset($_POST['postSubmit'])) {

        $user_id = $_POST['user_id'];
        $timestamp = time();
        $datum = date("d.m.Y - H:i:s", $timestamp);
        $message = $_POST['message'];

        $sql = "INSERT INTO posts (user_id, datum, message) VALUES ('$user_id', '$datum', '$message')";
        $result = $conn->query($sql);

        $eintrag = mysqli_affected_rows($conn);
        // Wenn zeile grösser als Null ist. //
        if ($eintrag > 0) {
            echo "<div class='ok'>";
            echo "Es wurde 1 zeile hinzugef&uuml;gt";
            echo "</div>"; 
            header("refresh: 1.5; index.php");
        } else {
            echo "<div class='error'>";
            echo "Es ist ein Fehler aufgetreten, ";
            echo "es wurde kein zeile hinzugef&uuml;gt";
            echo "</div>";
            header("refresh: 1.5; index.php");
        }

    }

}

/* [ANZEIGEN DER POSTINGS] */
function getPosting($conn) {

    $sql = "SELECT * FROM posts";
    $result = $conn->query($sql);
    // var_dump($sql);

    while ($zeile = mysqli_fetch_assoc($result)) {

        // user_id ist aus tabelle posts definiert //
        $id = $zeile['user_id'];

        $sql2 = "SELECT * FROM users WHERE id='$id'";
        $result2 = $conn->query($sql2);
        // var_dump($sql2);

        if ($zeile2 = $result2->fetch_assoc()) { 

            echo "<div class='comment-box'>"; 
                // Ausgabe des Profilebild //
                if ($zeile2['avatar'] == 0) {
                    $filename = "uploads/profile".$zeile2['id']."*";
                    $fileinfo = glob($filename);
                    $fileext = explode(".", $fileinfo[0]);
                    $fileactualext = $fileext[1];
                    echo "<img id='userimage' src='uploads/profile".$zeile2['id'].".".$fileactualext."' alt='Avatar'>";
                } else {
                    echo "<img id='userimage' src='uploads/profiledefault.jpg' alt='Avatar'>";
                }
                // Wenn status offline ist. //
                ($zeile2['status'] == "Jetzt Offline") ? $offline = "offline" : $offline = "";
                echo '<span class="status-dot ' .$offline.'" style="right:12px;top:-5px;">
                        <i class="fas fa-circle"></i>
                    </span>';
                    if (isset($_SESSION['id'])) {
                        if ($_SESSION['id'] == $zeile2['id']) {
                            echo "<a class='user' href='editprofile.php?id=".htmlspecialchars($zeile2['id'])."'>
                                <span>".htmlspecialchars($zeile2['vorname'])."\n".htmlspecialchars($zeile2['nachname'])."</span>
                            </a>";
                        } 
                    }  else {
                        echo htmlspecialchars($zeile2['vorname'])."\n".htmlspecialchars($zeile2['nachname']);
                    }  
                // Message anzeigen //
                echo "<small style='float:right;'>".$zeile['datum']."</small>";
                echo "<br><br><p>".$zeile['message']."</p>";
                // Wenn Session aktiv ist //
                if (isset($_SESSION['id'])) {
                    if ($_SESSION['id'] == $_SESSION['id']) {
                        echo "<form method='POST' action='".deletePosting($conn)."'>
                            <input type='hidden' name='id' value='".$zeile['id']."'>
                            <button type='submit' class='delete' name='deleteSubmit' onclick='return checkdelete()'>Delete</button>
                        </form>
                        <script>
                            function checkdelete() {
                                return confirm('Möchten Sie diese Daten wirklich löschen?');
                            }
                        </script>
                        <form method='post' action='editpost.php'>
                            <input type='hidden' name='id' value='".$zeile['id']."'>
                            <input type='hidden' name='user_id' value='".$zeile['user_id']."'>
                            <input type='hidden' name='datum' value='".$zeile['datum']."'>
                            <input type='hidden' name='message' value='".$zeile['message']."'>
                            <button type='submit' class='edit'>Edit</button>
                        </form>";
                    } 
                } else {
                    echo "<p class='commentmessage'>Sie müssen angemeldet sein, um antworten zu können!</p>";
                }
            echo "</div>";
        }
    }
}


/* [ÄNDERN DER POSTINGS DATEN] */
function editPosting($conn) {

    if (isset($_POST['editSubmit'])) {

        $id = $_POST['id'];
        $user_id = $_POST['user_id'];
        $datum = $_POST['datum'];
        $message = $_POST['message'];
        
        $sql = "UPDATE posts SET message='$message' WHERE id='$id'";
        $result = $conn->query($sql);
        header("Location: index.php");

    }
}


/* [LÖSCHEN DER POSTINGS DATEN] */
function deletePosting($conn) {

    if (isset($_POST['deleteSubmit'])) {

        $id = $_POST['id'];

        $sql = "DELETE FROM posts WHERE id='$id'";
        $result = $conn->query($sql);
        header("Location: index.php");

    }
}


/* [LOGINFUNCTION] */
function getLogin($conn) {
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
}




// Close //
?>
<?php
### Datenbank Verbindung zum Server ###
date_default_timezone_set('Europe/Berlin');
if($_SERVER['SERVER_NAME'] == 'localhost') {
    // localhost = Offline mkcoding
    $conn = new mysqli('localhost', 'root', '', 'test2');
} else {
    // 1&1 IONOS = Online  
    $conn = new mysqli('', '', '', '');
}
$conn->set_charset('utf8');

if($conn->connect_errno) {
    die('Sorry - gerade gibt es ein Verbindungsproblem!');
}
// Close //
?>
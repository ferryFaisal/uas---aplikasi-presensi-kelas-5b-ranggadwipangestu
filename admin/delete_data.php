<?php
require 'connect_db.php';

$email = $_GET['email'];

$sql = "DELETE FROM user WHERE email = '$email'";
if (mysqli_query($conn, $sql)) {

    header('Location: tables.php');
    ob_end_flush();

} else {
    echo "Error deleting record: " . mysqli_error($conn);
}

mysqli_close($conn);
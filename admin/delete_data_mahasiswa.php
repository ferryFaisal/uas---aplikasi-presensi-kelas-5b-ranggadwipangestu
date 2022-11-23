<?php
require 'connect_db.php';
session_start();

$nim = $_GET['nim'];

$sql = "DELETE FROM mahasiswa WHERE nim = '$nim'";
if (mysqli_query($conn, $sql)) {
    echo "Data deleted successfully";

    header('Location: tables-mahasiswa.php');
    ob_end_flush();

} else {
    echo "Error deleting record: " . mysqli_error($conn);
}

mysqli_close($conn);
//test
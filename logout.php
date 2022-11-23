<?php

session_start();

if (isset($_SESSION['loginfront'])) {
    unset($_SESSION['loginfront']);

    // session_destroy();

//
    header('location: login.php');
}
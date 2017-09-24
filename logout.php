<?php
    session_start();

    require_once "vendor/autoload.php";

    if (isset($_SESSION['user'])) {
        unset($_SESSION['user']);
    }

    header("Location: /index.php");

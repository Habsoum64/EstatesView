<?php
session_start();

function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}

function redirect_if_logged_in() {
    if (isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }
}

function get_user_type() {
    return isset($_SESSION['user_type']) ? $_SESSION['user_type'] : null;
}

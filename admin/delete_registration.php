<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Check if admin is logged in
if(!isset($_SESSION['adm_id'])) {
    header("location: login.php");
    exit();
}

// Check if registration ID is provided
if(!isset($_GET['id'])) {
    header("location: all_game_registration.php");
    exit();
}

$registration_id = $_GET['id'];

// Delete the registration
$sql = "DELETE FROM game_registration WHERE registration_id = ?";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $registration_id);

if(mysqli_stmt_execute($stmt)) {
    // Success - redirect with success message
    $_SESSION['success'] = "Registration deleted successfully";
    header("location: all_game_registration.php");
    exit();
} else {
    // Error - redirect with error message
    $_SESSION['error'] = "Error deleting registration. Please try again.";
    header("location: all_game_registration.php");
    exit();
}

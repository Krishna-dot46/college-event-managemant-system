<?php
include("connection/connect.php");
error_reporting(0);
session_start();

if(empty($_GET['game_id'])) {
    header('location: games.php');
    exit();
}

$game_id = intval($_GET['game_id']);

// Verify game exists
$sql = "SELECT * FROM games WHERE game_id = ?";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $game_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) == 0) {
    header('location: games.php');
    exit();
}

if(empty($_SESSION["user_id"])) {
    // Not logged in - redirect to login with return URL
    $return_url = urlencode("game_register.php?game_id=" . $game_id);
    header("location: login.php?redirect=" . $return_url);
    exit();
}

// Logged in - redirect to register page
header("location: game_register.php?game_id=" . $game_id);
exit();
?>

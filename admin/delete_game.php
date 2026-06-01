<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

mysqli_query($db,"DELETE FROM games WHERE game_id = '".$_GET['game_id']."'");
header("location:all_game.php");  

?>

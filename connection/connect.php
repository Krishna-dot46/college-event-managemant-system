<?php

//main connection file for both admin & front end
$servername = "localhost"; //server
$username = "root"; //username
$password = ""; //password
$dbname = "vbyte";  //database

// Create connection without database first
$db = mysqli_connect($servername, $username, $password);

// Check connection
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if (!mysqli_query($db, $sql)) {
    die("Error creating database: " . mysqli_error($db));
}

// Select the database
mysqli_select_db($db, $dbname);

// Import SQL file if tables don't exist
$check_table = mysqli_query($db, "SHOW TABLES LIKE 'users'");
if (mysqli_num_rows($check_table) == 0) {
    $sql_file = file_get_contents('DATABASE FILE/vbyte.sql');
    if (!mysqli_multi_query($db, $sql_file)) {
        die("Error importing database structure: " . mysqli_error($db));
    }
    while (mysqli_next_result($db)) {;} // Clear multi_query
}

?>
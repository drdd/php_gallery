<?php
$servername = "mysql";
$username = "myuser";
$password = "mypassword";
$dbname = "mydatabase";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
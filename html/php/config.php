<?php
$host = '127.0.0.1';
$port = 3306;
$dbname = 'simple_blog';
$dbuser = '@Usuario';
$dbpass = '@MalditaSenha1234567@';

// Create connection
$conn = new mysqli($host, $dbuser, $dbpass, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();
?>



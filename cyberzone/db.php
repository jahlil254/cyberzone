<?php
$host = 'localhost:3307';
$username = 'root';
$password = '';
$database = 'cyberzone';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

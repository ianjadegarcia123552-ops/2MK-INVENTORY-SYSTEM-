<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "construction_material_db";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    http_response_code(500);
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>
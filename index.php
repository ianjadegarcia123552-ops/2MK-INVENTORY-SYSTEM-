<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$host = "localhost";
$user = "root";
$password = "";
$database = "construction_material_db";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed: " . $conn->connect_error
    ]);
    exit;
}

$conn->set_charset("utf8mb4");

$action = $_GET["action"] ?? "";

if ($action === "test") {

    echo json_encode([
        "success" => true,
        "message" => "2MK HOLDINGS INC. database connected successfully!"
    ]);

    exit;
}

if ($action === "materials") {

    $result = $conn->query("SELECT * FROM materials ORDER BY id DESC");

    $materials = [];

    while ($row = $result->fetch_assoc()) {
        $materials[] = $row;
    }

    echo json_encode([
        "success" => true,
        "data" => $materials
    ]);

    exit;
}

if ($action === "projects") {

    $result = $conn->query("SELECT * FROM projects ORDER BY id DESC");

    $projects = [];

    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }

    echo json_encode([
        "success" => true,
        "data" => $projects
    ]);

    exit;
}

if ($action === "transactions") {

    $result = $conn->query("SELECT * FROM transactions ORDER BY id DESC");

    $transactions = [];

    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
    }

    echo json_encode([
        "success" => true,
        "data" => $transactions
    ]);

    exit;
}

echo json_encode([
    "success" => false,
    "message" => "Invalid API action"
]);

$conn->close();
?>
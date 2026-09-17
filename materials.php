<?php

header("Content-Type: application/json");

require_once "db.php";

$action = $_GET["action"] ?? "list";

if ($action === "list") {

    $result = $conn->query("
        SELECT *
        FROM materials
        ORDER BY id DESC
    ");

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

echo json_encode([
    "success" => false,
    "message" => "Invalid API action"
]);

?>
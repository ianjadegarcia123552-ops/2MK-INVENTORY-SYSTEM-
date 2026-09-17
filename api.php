<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require_once "db.php";

$method = $_SERVER["REQUEST_METHOD"];

if ($method === "OPTIONS") {
    exit;
}

/* GET - Load all materials */
if ($method === "GET") {

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


/* POST - Add material */
if ($method === "POST") {

    $input = json_decode(file_get_contents("php://input"), true);

    $material_name = $input["material_name"] ?? "";
    $category      = $input["category"] ?? "";
    $quantity      = $input["quantity"] ?? 0;
    $unit          = $input["unit"] ?? "";
    $unit_price    = $input["unit_price"] ?? 0;
    $location      = $input["location"] ?? "";
    $supplier      = $input["supplier"] ?? "";
    $remarks       = $input["remarks"] ?? "";

    $stmt = $conn->prepare("
        INSERT INTO materials
        (material_name, category, quantity, unit, unit_price, location, supplier, remarks)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssdsdsss",
        $material_name,
        $category,
        $quantity,
        $unit,
        $unit_price,
        $location,
        $supplier,
        $remarks
    );

    if ($stmt->execute()) {

        echo json_encode([
            "success" => true,
            "message" => "Material added successfully",
            "id" => $conn->insert_id
        ]);

    } else {

        http_response_code(500);

        echo json_encode([
            "success" => false,
            "message" => $stmt->error
        ]);
    }

    exit;
}


/* PUT - Update material */
if ($method === "PUT") {

    $input = json_decode(file_get_contents("php://input"), true);

    $id            = $input["id"] ?? 0;
    $material_name = $input["material_name"] ?? "";
    $category      = $input["category"] ?? "";
    $quantity      = $input["quantity"] ?? 0;
    $unit           = $input["unit"] ?? "";
    $unit_price     = $input["unit_price"] ?? 0;
    $location       = $input["location"] ?? "";
    $supplier       = $input["supplier"] ?? "";
    $remarks        = $input["remarks"] ?? "";

    $stmt = $conn->prepare("
        UPDATE materials SET
            material_name = ?,
            category = ?,
            quantity = ?,
            unit = ?,
            unit_price = ?,
            location = ?,
            supplier = ?,
            remarks = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "ssdsdsssi",
        $material_name,
        $category,
        $quantity,
        $unit,
        $unit_price,
        $location,
        $supplier,
        $remarks,
        $id
    );

    if ($stmt->execute()) {

        echo json_encode([
            "success" => true,
            "message" => "Material updated successfully"
        ]);

    } else {

        http_response_code(500);

        echo json_encode([
            "success" => false,
            "message" => $stmt->error
        ]);
    }

    exit;
}


/* DELETE - Delete material */
if ($method === "DELETE") {

    $input = json_decode(file_get_contents("php://input"), true);

    $id = $input["id"] ?? 0;

    $stmt = $conn->prepare("
        DELETE FROM materials
        WHERE id = ?
    ");

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        echo json_encode([
            "success" => true,
            "message" => "Material deleted successfully"
        ]);

    } else {

        http_response_code(500);

        echo json_encode([
            "success" => false,
            "message" => $stmt->error
        ]);
    }

    exit;
}


http_response_code(405);

echo json_encode([
    "success" => false,
    "message" => "Method not allowed"
]);

?>
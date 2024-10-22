<?php

header('Content-Type: application/json');

$pdo = require_once 'database.php';

if (!isset($_GET['lat']) || !isset($_GET['lon'])) {
    die('err');
}

$latitude = $_GET['lat'];
$longitude = $_GET['lon'];

$sql = "SELECT s.*,
    CONCAT(a.first_name, ' ', a.last_name) AS agent_full_name,
    (6371 * acos(cos(radians(:latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians(:longitude)) + sin(radians(:latitude)) * sin(radians(latitude)))) AS distance 
    FROM sites s
    JOIN agents a ON s.agent_id = a.id
    ORDER BY distance 
    LIMIT 5
";

$stmt = $pdo->prepare($sql);

$stmt->bindParam(':latitude', $latitude);
$stmt->bindParam(':longitude', $longitude);

$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($result) {
    http_response_code(200);

    echo json_encode($result);
}

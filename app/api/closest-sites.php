<?php

header('Content-Type: application/json');

$pdo = require_once 'database.php';

if (!isset($_GET['lon']) || !isset($_GET['lat'])) {
    die('err');
}

$longitude = $_GET['lon'];
$latitude = $_GET['lat'];
$radius = 500;

$latMin = $latitude - ($radius / 111);
$latMax = $latitude + ($radius / 111);
$lonMin = $longitude - ($radius / (111 * cos(deg2rad($latitude))));
$lonMax = $longitude + ($radius / (111 * cos(deg2rad($latitude))));

$sql = "SELECT s.id, s.site,
    CONCAT(a.first_name, ' ', a.last_name) AS agent_full_name,
    ST_Distance_Sphere(POINT(:longitude, :latitude), s.location) AS distance
    FROM sites s
    JOIN agents a ON s.agent_id = a.id
    WHERE ST_X(s.location) BETWEEN :lonmin AND :lonmax
        AND ST_Y(s.location) BETWEEN :latmin AND :latmax
    ORDER BY distance
    LIMIT 5
";

$stmt = $pdo->prepare($sql);

$stmt->bindParam(':longitude', $longitude);
$stmt->bindParam(':latitude', $latitude);
$stmt->bindParam(':lonmin', $lonMin);
$stmt->bindParam(':lonmax', $lonMax);
$stmt->bindParam(':latmin', $latMin);
$stmt->bindParam(':latmax', $latMax);

$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($result) {
    http_response_code(200);

    echo json_encode($result);
}

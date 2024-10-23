<?php

$host = 'mariadb';
$dbname = $_ENV['MYSQL_DATABASE'];
$username = $_ENV['MYSQL_USER'];
$password = $_ENV['MYSQL_PASSWORD'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

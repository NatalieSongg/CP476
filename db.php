<?php
$host = 'localhost';  // Database host
$dbname = 'StudentDB';  // Database name
$username = 'root';  // Database username
$password = 'Lucky#123';  // Database password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

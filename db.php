<?php
$host = 'localhost';  // Database host
$dbname = 'StudentDB';  // Database name
$username = 'root';  // Database username
$password = 'Lucky#123';  // Database password

try {
    // Create a new PDO (PHP Data Objects) connection to the MySQL database
    // This uses the provided host, database name, username, and password
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Set an attribute on the database connection to throw exceptions when an error occurs
    // This helps in debugging by providing detailed error messages
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If a connection error occurs, catch the exception and display an error message
    echo "Connection failed: " . $e->getMessage();
}
?>



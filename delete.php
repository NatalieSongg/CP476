<?php
// Start a session to manage user authentication and session data
session_start();

// Check if the user is logged in by verifying the session variable 'loggedin'
// If the user is not logged in, redirect them to the login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("Location: login.php"); // Redirect to login page
    exit; // Ensure the script stops execution after redirection
}

// Include the database connection file to allow database operations
include 'db.php';

// Check if the 'id' parameter is passed in the URL
// This ID represents the student that needs to be deleted
if (isset($_GET['id'])) {
    // Retrieve the 'id' value from the URL and store it in a variable
    $idToDelete = $_GET['id'];

    // Prepare a SELECT statement to check if the student record exists in the database
    $stmt = $conn->prepare("SELECT * FROM `NameTable` WHERE `StudentID` = :studentId");
    
    // Bind the 'id' parameter to the SQL query to prevent SQL injection attacks
    $stmt->bindParam(':studentId', $idToDelete);

    // Execute the query
    $stmt->execute();

    // Fetch the student record as an associative array
    $studentRecord = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the student record exists in the database
    if ($studentRecord) {
        // Prepare a DELETE statement to remove the student record from the database
        $stmtDelete = $conn->prepare("DELETE FROM `NameTable` WHERE `StudentID` = :studentId");
        
        // Bind the 'id' parameter to the DELETE query to prevent SQL injection
        $stmtDelete->bindParam(':studentId', $idToDelete);

        // Execute the DELETE query to remove the student from the database
        $stmtDelete->execute();

        // Redirect back to the dashboard (index.php) with a success message in the URL
        header("Location: index.php?delete=success");
        exit(); // Ensure the script stops executing after redirection
    } else {
        // If the student record does not exist, redirect with an error message
        header("Location: index.php?delete=error");
        exit();
    }
} else {
    // If the 'id' parameter is not set in the URL, redirect with an error message
    header("Location: index.php?delete=error");
    exit();
}
?>

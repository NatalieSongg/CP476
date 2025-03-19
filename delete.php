<?php
session_start();


// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("Location: login.php");
    exit;
}

include 'db.php'; // Include your DB connection

// Check if the 'id' parameter is passed and is valid
if (isset($_GET['id'])) {
    $idToDelete = $_GET['id'];

    // Prepare the SELECT query to fetch the student record from the database
    $stmt = $conn->prepare("SELECT * FROM `NameTable` WHERE `StudentID` = :studentId");
    $stmt->bindParam(':studentId', $idToDelete);
    $stmt->execute();
    $studentRecord = $stmt->fetch(PDO::FETCH_ASSOC);

    // If the student record is found, proceed with the deletion
    if ($studentRecord) {
        // Prepare the DELETE query to remove the student record from the database
        $stmtDelete = $conn->prepare("DELETE FROM `NameTable` WHERE `StudentID` = :studentId");
        $stmtDelete->bindParam(':studentId', $idToDelete);
        $stmtDelete->execute();

        // Redirect back to the dashboard with a success message
        header("Location: dashboard.php?delete=success");
        exit();
    } else {
        // Redirect with an error message if the student is not found
        header("Location: dashboard.php?delete=error");
        exit();
    }
} else {
    // Redirect with an error message if no ID is passed
    header("Location: dashboard.php?delete=error");
    exit();
}



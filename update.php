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
    $idToUpdate = $_GET['id'];

    // Prepare the SELECT query to fetch the student record from the database
    $stmt = $conn->prepare("SELECT * FROM `NameTable` WHERE `StudentID` = :studentId");
    $stmt->bindParam(':studentId', $idToUpdate);
    $stmt->execute();
    $studentRecord = $stmt->fetch(PDO::FETCH_ASSOC);

    // If the student record is found, display the update form
    if ($studentRecord) {
        echo "<h3>Update Student</h3>";
        echo "<form method='POST' action='update.php?id=" . $studentRecord['StudentID'] . "'>
                <input type='hidden' name='student_id' value='" . $studentRecord['StudentID'] . "'>
                <label for='student_id'>Student ID: </label>
                <input type='text' name='student_id' value='" . htmlspecialchars($studentRecord['StudentID']) . "' required><br>
                <label for='name'>Name: </label>
                <input type='text' name='name' value='" . htmlspecialchars($studentRecord['StudentName']) . "' required><br>
                <input type='submit' name='update_student' value='Update'>
              </form>";
    } else {
        echo "<h3>No student found with that ID.</h3>";
    }
}

// Handle the form submission for the update
if (isset($_POST['update_student'])) {
    $studentId = $_POST['student_id'];  // New Student ID
    $updatedName = $_POST['name'];  // Updated Name

    // Prepare and execute the UPDATE query, updating both student name and student number (ID)
    try {
        // Update the StudentName and StudentID
        $stmt = $conn->prepare("UPDATE `NameTable` SET `StudentID` = :studentId, `StudentName` = :name WHERE `StudentID` = :originalStudentId");
        $stmt->bindParam(':studentId', $studentId); // Updated student ID
        $stmt->bindParam(':name', $updatedName);
        $stmt->bindParam(':originalStudentId', $_POST['student_id']); // The original student ID (for reference in the WHERE clause)
        $stmt->execute();

        // Redirect back to dashboard after successful update
        header("Location: index.php?update=success");
        exit();
    } catch (PDOException $e) {
        echo "Error updating record: " . $e->getMessage();
    }
}
?>

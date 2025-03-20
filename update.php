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
                $stmt->bindParam(':originalStudentId', $_GET['id']); // The original student ID (for reference in the WHERE clause)
                $stmt->execute();

                // Set a success message
                $message = "<div class='message success-message'>Student updated successfully!</div>";
            } catch (PDOException $e) {
                $message = "<div class='message error-message'>Error updating record: " . $e->getMessage() . "</div>";
            }
        }
    } else {
        echo "<h3>No student found with that ID.</h3>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            height: 400px; /* Fixed height for the container */
        }
        h3 {
            color: #333;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .success-message {
            color: green;
        }
        .error-message {
            color: red;
        }
        form {
            margin-top: 30px; /* Increased space between form and Back button */
        }
        input[type="text"] {
            padding: 8px;
            width: 70%;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        input[type="submit"] {
            padding: 8px 15px;
            border: none;
            background: #28a745;
            color: white;
            cursor: pointer;
            border-radius: 4px;
            margin-top: 20px;
        }
        input[type="submit"]:hover {
            background: #218838;
        }
        .back-btn {
            margin-bottom: 10px; 
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .back-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Back to Dashboard Button -->
    <a class="back-btn" href="index.php">Back to Dashboard</a>

    <!-- Display success or error message -->
    <?php echo $message ?? ''; ?>

    <!-- Update form -->
    <?php
    if (isset($studentRecord)) {
        echo "
            <form method='POST' action='update.php?id=" . $studentRecord['StudentID'] . "'>
                <input type='hidden' name='student_id' value='" . $studentRecord['StudentID'] . "'>
                <label for='student_id'>Student ID: </label>
                <input type='text' name='student_id' value='" . htmlspecialchars($studentRecord['StudentID']) . "' required><br>
                <label for='name'>Name: </label>
                <input type='text' name='name' value='" . htmlspecialchars($studentRecord['StudentName']) . "' required><br>
                <input type='submit' name='update_student' value='Update'>
            </form>
        ";
    }
    ?>
</div>

</body>
</html>

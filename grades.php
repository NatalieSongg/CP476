<?php
// Start the session to manage user authentication and session data
session_start();

// Check if the user is logged in
// If the session variable 'loggedin' is not set or is false, redirect to the login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("Location: login.php"); // Redirect to login page
    exit; // Stop script execution after redirection
}

// Include the database connection file to establish a connection with the MySQL database
include 'db.php';

// Check if 'id' parameter is passed in the URL
if (isset($_GET['id'])) {
    // Retrieve the student ID from the URL parameter and store it in a variable
    $studentId = $_GET['id'];

    // Prepare a SQL query to fetch the student's grades from the 'CourseTable'
    $stmt = $conn->prepare("SELECT * FROM `CourseTable` WHERE `StudentID` = :studentId");
    $stmt->bindParam(':studentId', $studentId); // Bind the student ID parameter securely
    $stmt->execute(); // Execute the query
    $grades = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all matching records as an associative array

    // Prepare a SQL query to fetch the student's name from 'NameTable'
    $stmt = $conn->prepare("SELECT `StudentName` FROM `NameTable` WHERE `StudentID` = :studentId");
    $stmt->bindParam(':studentId', $studentId); // Bind the student ID securely
    $stmt->execute(); // Execute the query
    $studentName = $stmt->fetch(PDO::FETCH_ASSOC)['StudentName']; // Retrieve the student name
} else {
    // If no 'id' parameter is provided in the URL, display an error message and exit the script
    echo "Student ID is missing!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Grades</title>
    <style>
        /* General page styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        /* Container for the content */
        .container {
            width: 60%;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        /* Heading styling */
        h2 {
            color: #333;
            margin-bottom: 10px;
        }
        /* Button container styling */
        .buttons {
            margin-bottom: 20px;
        }
        /* General button styling */
        .buttons a {
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 4px;
            color: white;
            margin: 5px;
            display: inline-block;
        }
        /* Dashboard button styling */
        .dashboard-btn {
            background: #007bff;
        }
        .dashboard-btn:hover {
            background: #0056b3;
        }
        /* Logout button styling */
        .logout-btn {
            background: #dc3545;
        }
        .logout-btn:hover {
            background: #b52b38;
        }
        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: #fff;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        /* Styling for when no grades are found */
        .no-grades {
            color: #888;
            font-style: italic;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Grades for <?php echo htmlspecialchars($studentName); ?></h2>

    <div class="buttons">
        <a href="index.php" class="dashboard-btn">Back to Dashboard</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <!-- Display the student's grades if found -->
    <?php if (count($grades) > 0): ?>
        <h3>Course Grades</h3>
        <table>
            <tr>
                <th>Course Code</th>
                <th>Grade 1</th>
                <th>Grade 2</th>
                <th>Grade 3</th>
                <th>Grade 4</th>
                <th>Final Grade</th>
            </tr>
            <?php foreach ($grades as $grade): ?>
                <tr>
                    <td><?php echo htmlspecialchars($grade['CourseCode']); ?></td>
                    <td><?php echo htmlspecialchars($grade['Grade1']); ?></td>
                    <td><?php echo htmlspecialchars($grade['Grade2']); ?></td>
                    <td><?php echo htmlspecialchars($grade['Grade3']); ?></td>
                    <td><?php echo htmlspecialchars($grade['Grade4']); ?></td>
                    <td>
                        <?php 
                            // Calculate final grade based on weighted sum
                            $finalGrade = ($grade['Grade1'] * 0.20) + ($grade['Grade2'] * 0.20) + ($grade['Grade3'] * 0.20) + ($grade['Grade4'] * 0.40);
                            echo number_format($finalGrade, 1); // Display final grade with one decimal place
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <!-- Display a message if no grades are found for the student -->
        <p class="no-grades">No grades found for this student.</p>
    <?php endif; ?>
</div>

</body>
</html>

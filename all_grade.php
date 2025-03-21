<?php
session_start(); // Start the session to track user authentication

// Check if the user is logged in; if not, redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("Location: login.php"); // Redirect to login page
    exit; // Stop script execution
}

include 'db.php'; // Include the database connection file

// Fetch all students along with their grades by joining NameTable and CourseTable
$stmt = $conn->prepare("
    SELECT 
        NameTable.StudentID,  -- Student ID from NameTable
        NameTable.StudentName,  -- Student name from NameTable
        CourseTable.CourseCode,  -- Course code from CourseTable
        CourseTable.Grade1,  -- First grade
        CourseTable.Grade2,  -- Second grade
        CourseTable.Grade3,  -- Third grade
        CourseTable.Grade4  -- Fourth grade
    FROM NameTable
    LEFT JOIN CourseTable ON NameTable.StudentID = CourseTable.StudentID  -- Left join to include students even if they have no grades
    ORDER BY NameTable.StudentID, CourseTable.CourseCode  -- Sort by student ID and course code
");
$stmt->execute(); // Execute the query
$grades = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results as an associative array
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Students' Grades</title>
    <style>
        /* Basic styling for the page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        /* Back and Logout button styling */
        .back-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background: #17a2b8;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .back-btn:hover {
            background: #138496;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>All Students' Grades</h2>
    <a class="back-btn" href="index.php">Back to Home</a> | 
    <a class="back-btn" href="logout.php" style="background: #dc3545;">Logout</a>

    <!-- Table displaying student grades -->
    <table>
        <tr>
            <th>Student ID</th>
            <th>Student Name</th>
            <th>Course Code</th>
            <th>Grade 1</th>
            <th>Grade 2</th>
            <th>Grade 3</th>
            <th>Grade 4</th>
            <th>Final Grade</th>
        </tr>
        
        <?php if (!empty($grades)): ?>
            <?php foreach ($grades as $grade): ?>
                <tr>
                    <td><?php echo $grade['StudentID']; ?></td>
                    <td><?php echo $grade['StudentName']; ?></td>
                    <td><?php echo $grade['CourseCode'] ? $grade['CourseCode'] : 'N/A'; ?></td>
                    <td><?php echo $grade['Grade1'] !== null ? $grade['Grade1'] : '-'; ?></td>
                    <td><?php echo $grade['Grade2'] !== null ? $grade['Grade2'] : '-'; ?></td>
                    <td><?php echo $grade['Grade3'] !== null ? $grade['Grade3'] : '-'; ?></td>
                    <td><?php echo $grade['Grade4'] !== null ? $grade['Grade4'] : '-'; ?></td>
                    <td>
                        <?php 
                        // Calculate final grade if all grades are available
                        if ($grade['Grade1'] !== null && $grade['Grade2'] !== null && $grade['Grade3'] !== null && $grade['Grade4'] !== null) {
                            $finalGrade = ($grade['Grade1'] * 0.20) + ($grade['Grade2'] * 0.20) + ($grade['Grade3'] * 0.20) + ($grade['Grade4'] * 0.40);
                            echo number_format($finalGrade, 1); // Display final grade with one decimal place
                        } else {
                            echo '-'; // If any grade is missing, show '-'
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="8">No grades available.</td></tr>
        <?php endif; ?>
    </table>
</div>

</body>
</html>

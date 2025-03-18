<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("Location: login.php");
    exit;
}

include 'db.php';

// Fetch student ID from the URL
if (isset($_GET['id'])) {
    $studentId = $_GET['id'];

    // Get the student's grades from the CourseTable
    $stmt = $conn->prepare("SELECT * FROM `CourseTable` WHERE `StudentID` = :studentId");
    $stmt->bindParam(':studentId', $studentId);
    $stmt->execute();
    $grades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get student name for displaying
    $stmt = $conn->prepare("SELECT `StudentName` FROM `NameTable` WHERE `StudentID` = :studentId");
    $stmt->bindParam(':studentId', $studentId);
    $stmt->execute();
    $studentName = $stmt->fetch(PDO::FETCH_ASSOC)['StudentName'];
} else {
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
</head>
<body>
    <h2>Grades for <?php echo $studentName; ?></h2>
    <a href="index.php">Back to Search</a> | <a href="logout.php">Logout</a>

    <!-- Grades Table -->
    <?php if (count($grades) > 0): ?>
        <h3>Course Grades</h3>
        <table border="1">
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
                    <td><?php echo $grade['CourseCode']; ?></td>
                    <td><?php echo $grade['Grade1']; ?></td>
                    <td><?php echo $grade['Grade2']; ?></td>
                    <td><?php echo $grade['Grade3']; ?></td>
                    <td><?php echo $grade['Grade4']; ?></td>
                    <td>
                        <?php 
                            // Calculate final grade
                            $finalGrade = ($grade['Grade1'] + $grade['Grade2'] + $grade['Grade3'] + $grade['Grade4']) / 4;
                            echo round($finalGrade, 2); // Display rounded final grade
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No grades found for this student.</p>
    <?php endif; ?>
</body>
</html>

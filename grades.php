<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("Location: login.php");
    exit;
}

include 'db.php';

if (isset($_GET['StudentID'])) {
    $id = $_GET['StudentID'];

    // Get the student name from NameTable
    $stmt = $conn->prepare("SELECT * FROM `NameTable` WHERE `StudentID` = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
        // Get the student grades from CourseTable
        $stmt = $conn->prepare("SELECT * FROM `CourseTable` WHERE `StudentID` = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Prepare an array to store the final grades for each course
        $finalGrades = [];

        // Loop through each course and calculate the final grade
        foreach ($courses as $course) {
            // Extract test scores and final exam score from the database
            $test1 = $course['Grade1'];
            $test2 = $course['Grade2'];
            $test3 = $course['Grade3'];
            $finalExam = $course['Grade4'];

            // Calculate the final grade based on the formula
            $finalGrade = ($test1 * 0.20) + ($test2 * 0.20) + ($test3 * 0.20) + ($finalExam * 0.40);
            $finalGrades[] = [
                'course_code' => $course['CourseCode'],
                'final_grade' => number_format($finalGrade, 1)  // Format the grade to 1 decimal place
            ];
        }
    }
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
    <h2>Student Final Grades</h2>
    <a href="logout.php">Logout</a>

    <?php if (isset($student)): ?>
        <h3>Student Information</h3>
        <p><strong>Student ID:</strong> <?php echo $student['StudentID']; ?></p>
        <p><strong>Student Name:</strong> <?php echo $student['StudentName']; ?></p>

        <h3>Course Grades</h3>
        <table border="1">
            <tr>
                <th>Course Code</th>
                <th>Final Grade</th>
            </tr>
            <?php foreach ($finalGrades as $grade): ?>
                <tr>
                    <td><?php echo $grade['CourseCode']; ?></td>
                    <td><?php echo $grade['final_grade']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No student found with the given ID.</p>
    <?php endif; ?>
</body>
</html>

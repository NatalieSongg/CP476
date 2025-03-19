<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("Location: login.php");
    exit;
}

include 'db.php';

// Get search results if a search term was submitted
$searchResults = [];
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search_term'];
    $stmt = $conn->prepare("SELECT * FROM `NameTable` WHERE `StudentName` LIKE :search_term");
    $searchTerm = "%" . $searchTerm . "%";
    $stmt->bindParam(':search_term', $searchTerm);
    $stmt->execute();
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Handle delete or update success/error messages
$message = '';
if (isset($_GET['delete'])) {
    if ($_GET['delete'] == 'success') {
        $message = "<p style='color: green;'>Record Deleted Successfully!</p>";
    } elseif ($_GET['delete'] == 'error') {
        $message = "<p style='color: red;'>Error: No record found to delete.</p>";
    }
} elseif (isset($_GET['update'])) {
    if ($_GET['update'] == 'success') {
        $message = "<p style='color: green;'>Record Updated Successfully!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
</head>
<body>
    <h2>Welcome to the Student Management System</h2>

    <!-- Display success or error message -->
    <?php echo $message; ?>

    <a href="logout.php">Logout</a>
    <br><br>

   <!-- Button to go to Dashboard -->
    <!-- <a href="dashboard.php">
        <button type="button">Go to Dashboard</button>
    </a> -->

    <!-- Search Form -->
    <form action="index.php" method="POST">
        <label for="search_term">Search by Name: </label>
        <input type="text" name="search_term" required>
        <input type="submit" name="search" value="Search">
    </form>

    <!-- Search Results -->
    <?php if (count($searchResults) > 0): ?>
        <h3>Search Results</h3>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($searchResults as $student): ?>
                <tr>
                    <td><?php echo $student['StudentID']; ?></td>
                    <td><?php echo $student['StudentName']; ?></td>
                    <td>
                        <a href="update.php?id=<?php echo $student['StudentID']; ?>">Update</a> |
                        <a href="delete.php?id=<?php echo $student['StudentID']; ?>">Delete</a> |
                        <a href="grades.php?id=<?php echo $student['StudentID']; ?>">View Grades</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>

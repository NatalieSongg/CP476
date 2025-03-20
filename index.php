<?php
// Start the session to track user login status
session_start();

// Check if the user is logged in; if not, redirect to the login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("Location: login.php"); // Redirect to login page
    exit; // Ensure script execution stops after redirection
}

// Include the database connection file to interact with the database
include 'db.php';

// Initialize variables to store search results and messages
$searchResults = []; // Array to hold search results
$searchMessage = ''; // Message to display if no results are found

// Check if the search form was submitted
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search_term']; // Get the search input from the form
    
    // Prepare an SQL statement to search for student names using a wildcard
    $stmt = $conn->prepare("SELECT * FROM `NameTable` WHERE `StudentName` LIKE :search_term");
    
    // Add wildcards to search term to allow partial matches
    $searchTerm = "%" . $searchTerm . "%";
    
    // Bind the search term parameter securely to prevent SQL injection
    $stmt->bindParam(':search_term', $searchTerm);
    
    // Execute the query
    $stmt->execute();
    
    // Fetch the results as an associative array
    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // If no results are found, display an error message
    if (empty($searchResults)) {
        $searchMessage = "<p class='error-message'>No records found for the given name.</p>";
    }
}

// Initialize a variable to store messages for delete or update operations
$message = '';

// Check if a delete or update action was performed and display the appropriate message
if (isset($_GET['delete'])) {
    if ($_GET['delete'] == 'success') {
        $message = "<p class='success-message'>Record Deleted Successfully!</p>";
    } elseif ($_GET['delete'] == 'error') {
        $message = "<p class='error-message'>Error: No record found to delete.</p>";
    }
} elseif (isset($_GET['update'])) {
    if ($_GET['update'] == 'success') {
        $message = "<p class='success-message'>Record Updated Successfully!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
    <style>
        /* Basic styles for page layout and formatting */
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
        }
        h2 {
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
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 8px;
            width: 70%;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            padding: 8px 15px;
            border: none;
            background: #28a745;
            color: white;
            cursor: pointer;
            border-radius: 4px;
        }
        input[type="submit"]:hover {
            background: #218838;
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
        .actions a {
            text-decoration: none;
            margin: 0 5px;
            padding: 5px 10px;
            border-radius: 4px;
            color: white;
        }
        .update-btn {
            background: #ffc107;
        }
        .delete-btn {
            background: #dc3545;
        }
        .grades-btn {
            background: #17a2b8;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Welcome to the Student Management System</h2>

    <!-- Display any success or error messages related to actions -->
    <?php echo $message; ?>

    <!-- Logout button to allow users to sign out -->
    <a class="logout" href="logout.php">Logout</a>

    <br><br>

    <!-- Search Form: Users can enter a student name to search -->
    <form action="index.php" method="POST">
        <input type="text" name="search_term" placeholder="Enter student name..." required>
        <input type="submit" name="search" value="Search">
    </form>

    <!-- Button to view all students' grades -->
    <a class="view-grades-btn" href="all_grade.php">View All Grades</a>

    <!-- Display message if no search results are found -->
    <?php echo $searchMessage; ?>

    <!-- Display search results in a table if there are any -->
    <?php if (!empty($searchResults)): ?>
        <h3>Search Results</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($searchResults as $student): ?>
                <tr>
                    <td><?php echo $student['StudentID']; ?></td>
                    <td><?php echo $student['StudentName']; ?></td>
                    <td class="actions">
                        <!-- Update, Delete, and View Grades buttons with respective student ID -->
                        <a class="update-btn" href="update.php?id=<?php echo $student['StudentID']; ?>">Update</a>
                        <a class="delete-btn" href="delete.php?id=<?php echo $student['StudentID']; ?>">Delete</a>
                        <a class="grades-btn" href="grades.php?id=<?php echo $student['StudentID']; ?>">View Grades</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

</body>
</html>

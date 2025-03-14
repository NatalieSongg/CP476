<?php
// dashboard.php

session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Example data (In real-world applications, data will come from a database)
$records = [
    ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
    ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com'],
    ['id' => 3, 'name' => 'Sam Brown', 'email' => 'sam@example.com']
];

// Handle delete functionality
if (isset($_GET['delete'])) {
    $idToDelete = $_GET['delete'];
    // Find the record and remove it
    foreach ($records as $key => $record) {
        if ($record['id'] == $idToDelete) {
            unset($records[$key]);
            break;
        }
    }
    echo "<h3>Record Deleted Successfully!</h3>";
}

// Handle update functionality
if (isset($_GET['update'])) {
    $idToUpdate = $_GET['update'];
    // For simplicity, we'll just change the name
    foreach ($records as &$record) {
        if ($record['id'] == $idToUpdate) {
            $record['name'] = 'Updated Name';
        }
    }
    echo "<h3>Record Updated Successfully!</h3>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    <h3>Manage Records</h3>

    <!-- Search Form -->
    <form action="" method="POST">
        <input type="text" name="search" placeholder="Search by name" required>
        <input type="submit" value="Search">
    </form>

    <!-- Records Table -->
    <h4>Records:</h4>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php
        if (isset($_POST['search'])) {
            $searchTerm = $_POST['search'];
            $records = array_filter($records, function($record) use ($searchTerm) {
                return strpos(strtolower($record['name']), strtolower($searchTerm)) !== false;
            });
        }

        foreach ($records as $record) {
            echo "<tr>
                    <td>{$record['id']}</td>
                    <td>{$record['name']}</td>
                    <td>{$record['email']}</td>
                    <td>
                        <a href='dashboard.php?update={$record['id']}'>Update</a> |
                        <a href='dashboard.php?delete={$record['id']}'>Delete</a>
                    </td>
                  </tr>";
        }
        ?>
    </table>

    <a href="logout.php">Logout</a>
</body>
</html>
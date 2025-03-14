<?php
session_start();  // Start the session

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Assuming a hardcoded username and password for simplicity
    $username = 'admin';
    $password = 'password';

    if ($_POST['username'] === $username && $_POST['password'] === $password) {
        $_SESSION['loggedin'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid login credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" action="login.php">
        <label for="username">Username: </label>
        <input type="text" name="username" required><br><br>

        <label for="password">Password: </label>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>

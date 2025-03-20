<?php
session_start();  // Start the session to track user authentication status

// Check if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    // Redirect to the main dashboard (index.php) if the user is already logged in
    header("Location: index.php");
    exit; // Stop further execution
}

// Check if the form has been submitted using the POST method
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Hardcoded credentials for authentication (for demonstration purposes)
    $username = 'admin';
    $password = 'password';

    // Validate user input by checking if the entered username and password match
    if ($_POST['username'] === $username && $_POST['password'] === $password) {
        $_SESSION['loggedin'] = true;  // Set session variable to indicate user is logged in
        header("Location: index.php"); // Redirect to the main dashboard
        exit; // Stop further execution
    } else {
        $error = "Invalid login credentials!"; // Store an error message for invalid login
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Reset margins and padding for all elements */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        
        /* Center the login container in the middle of the screen */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full screen height */
            background: linear-gradient(135deg, #007bff, #6610f2); /* Gradient background */
        }

        /* Style for the login box */
        .login-container {
            background: white;
            padding: 25px;
            width: 350px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        /* Style for the heading inside the login box */
        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        /* Style for the login form */
        .login-container form {
            display: flex;
            flex-direction: column;
        }

        /* Style for form labels */
        .login-container label {
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
        }

        /* Style for input fields */
        .login-container input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        /* Style for the login button */
        .login-container input[type="submit"] {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        /* Change button background on hover */
        .login-container input[type="submit"]:hover {
            background: #0056b3;
        }

        /* Style for displaying error messages */
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>

        <!-- Display an error message if login fails -->
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>

        <!-- Login form -->
        <form method="POST" action="login.php">
            <label for="username">Username:</label>
            <input type="text" name="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <input type="submit" value="Login">
        </form>
    </div>

</body>
</html>

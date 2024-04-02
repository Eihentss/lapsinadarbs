<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="frontend/css/login.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <input type="submit" value="Login">
        </form>

        <?php
        session_start();

        // Include config.php to establish a connection with the database
        require 'backend/config.php';

        // Check if form data is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get form data
            $username = $_POST["username"];
            $password = $_POST["password"];

            try {
                // Check user data in the database
                $sql = "SELECT * FROM Users WHERE username=:username AND password=:password";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['username' => $username, 'password' => $password]);
                $result = $stmt->fetchAll();

                if (count($result) > 0) {
                    // Get user information to determine the user's role
                    $row = $result[0];
                    $role = $row['role'];
                
                    // Store user information in the session
                    $_SESSION["username"] = $username;
                    $_SESSION["role"] = $role;
                
                    // Depending on the user's role, redirect to the appropriate page
                    if ($role == 'user') {
                        header("Location: user.php");
                    } else {
                        header("Location: admin.php");
                    }
                } else {
                    echo "Invalid username or password.";
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
        ?>
    </div>

    <a href="register.php"> register</a>
</body>
</html>

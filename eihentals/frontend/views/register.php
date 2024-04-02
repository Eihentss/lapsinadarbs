<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/register.css">
</head>
<body>
    <h2>Register</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Register">
    </form>

    <div class="login-link">
        <a href="login.php">Ieiet</a>
    </div>
</body>
</html>

<?php
session_start();
// Include config.php to establish a connection with the database
require '../../backend/config.php';

// Pārbaudam, vai formas dati ir nosūtīti
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Iegūstam formas datus
    $username = $_POST["username"];
    $password = $_POST["password"];
    $role = "user"; // Piešķiram visiem jaunajiem lietotājiem lomu "user"
    $_SESSION["role"] = $role;

    try {
        // Pārbaudam, vai lietotājvārds jau ir datu bāzē
        $check_sql = "SELECT * FROM Users WHERE username = ?";
        $stmt = $pdo->prepare($check_sql);
        $stmt->execute([$username]);
        $existing_user = $stmt->fetch();

        if ($existing_user) {
            echo "Username already exists!";
        } else {
            // Saglabājam lietotāju datubāzē
            $sql = "INSERT INTO Users (username, password, role) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $password, $role]);
            echo "Registration successful!";
        }
        
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

</body>
</html>

<?php
session_start();

$host = "localhost";
$user = "#";
$password = "#";
$dbname = "dokado_db";
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            header("Location: index.php");
        } else {
            echo "Invalid credentials";
        }
    } elseif (isset($_POST['register'])) {
        $new_username = $_POST['new_username'];
        $new_password = $_POST['new_password'];
        
        $sql = "INSERT INTO users (username, password, role) VALUES ('$new_username', '$new_password', 0)";
        if ($conn->query($sql) === TRUE) {
            echo "Registration successful. You can now log in.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script>
        function toggleForm() {
            var loginForm = document.getElementById("loginForm");
            var registerForm = document.getElementById("registerForm");
            var toggleButton = document.getElementById("toggleButton");
            var heading = document.getElementById("heading");

            if (loginForm.style.display === "block") {
                loginForm.style.display = "none";
                registerForm.style.display = "block";
                toggleButton.textContent = "Back to Login";
                heading.textContent = "Register";
            } else {
                loginForm.style.display = "block";
                registerForm.style.display = "none";
                toggleButton.textContent = "Register";
                heading.textContent = "Login";
            }
        }
    </script>
</head>
<body>
    <h1 id="heading">Login</h1>
    <form id="loginForm" action="" method="post" style="display: block;">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <button type="submit" name="login">Login</button>
    </form>

    <form id="registerForm" action="" method="post" style="display: none;">
        <label for="new_username">New Username:</label>
        <input type="text" id="new_username" name="new_username" required><br>
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br>
        <button type="submit" name="register">Register</button>
    </form>
    
    <button id="toggleButton" onclick="toggleForm()">Register</button>
</body>
</html>

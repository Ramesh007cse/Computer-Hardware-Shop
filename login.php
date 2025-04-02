<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $remember_me = isset($_POST["remember_me"]);

    // Check the database for the user
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $hashed_password);
    
    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $username;
            session_regenerate_id(); // Regenerate session ID for security

            // Set Remember Me Cookie if selected (with Secure and HttpOnly flags)
            if ($remember_me) {
                setcookie("user_id", $id, time() + (86400 * 30), "/", "", true, true); // 30 days cookie
                setcookie("username", $username, time() + (86400 * 30), "/", "", true, true);
            }

            // Redirect to chs.php after successful login
            header("Location: chs.php");
            exit();
        } else {
            $error_message = "Invalid credentials. Please try again.";
        }
    } else {
        $error_message = "No user found with that email address.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    
    <style>
        /* Error message styling */
        .error-message {
            background-color: #f44336;
            color: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
        }
        body {
            background-image: url(./reglog.png);
        }
    </style>
    </style>
    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var passwordToggle = document.getElementById("togglePassword");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                passwordToggle.textContent = "Hide Password";
            } else {
                passwordField.type = "password";
                passwordToggle.textContent = "Show Password";
            }
        }

        function validateForm() {
            var errorMessage = document.getElementById("error-message");
            var password = document.getElementById("password").value;
            var email = document.getElementById("email").value;

            if (email == "" || password == "") {
                errorMessage.textContent = "Both fields are required!";
                errorMessage.style.display = "block";
                return false;
            }

            // Optional: You can also validate email format here using regex
            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailPattern.test(email)) {
                errorMessage.textContent = "Please enter a valid email address.";
                errorMessage.style.display = "block";
                return false;
            }

            return true;
        }
    </script>
</head>
<body background="./reglog.png">
    <div class="container">
        <div class="form-container">
            <h2>Login</h2>
            <form method="POST" name="loginForm" onsubmit="return validateForm()">
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <span id="togglePassword" class="show-password" onclick="togglePassword()">Show Password</span>
                </div>

                <!-- Remember Me Option -->
                <div class="input-group">
                    <label>
                        <input type="checkbox" name="remember_me"> Remember Me
                    </label>
                </div>

                <!-- Error Message -->
                <?php if (isset($error_message)): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn-submit">Login</button>
            </form>

            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>

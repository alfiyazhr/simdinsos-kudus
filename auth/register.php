<?php
session_start();
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $userExists = $stmt->fetchColumn();

    if ($userExists) {
        $error = "Username sudah digunakan, silakan pilih username lain!";
    } else {
        // Encrypt password and insert new user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->execute([
            'username' => $username,
            'password' => $hashed_password
        ]);

        header('Location: login.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Register - DINSOS P3AP2KB</title>
    <link rel="icon" href="../admin/assets/logokudus.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .login-container {
            max-width: 400px;
            margin: 80px auto;
        }
        .header {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            background-color: #004085;
            color: white;
        }
        .header img {
            max-width: 60px;
            margin-right: 10px;
        }
        .header h4 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
        }
        .header h5 {
            margin: 0;
            font-size: 1rem;
            font-weight: 400;
        }
        .btn-primary {
            background-color: #004085;
            border-color: #004085;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="../assets/logokudus.png" alt="Logo Kudus">
        <div>
            <h4>DINSOS P3AP2KB</h4>
            <h5>Kabupaten Kudus</h5>
        </div>
    </div>
    <div class="container">
        <div class="login-container">
            <div class="card shadow-sm p-4">
                <h3 class="text-center">User Register</h3>
                <div id="serverError" class="alert alert-danger" role="alert" style="display: <?= isset($error) ? 'block' : 'none' ?>">
                    <?= isset($error) ? $error : '' ?>
                </div>
                <div id="passwordError" class="alert alert-danger" role="alert" style="display: none">
                    Password tidak cocok!
                </div>
                <form id="registerForm" method="POST" action="" onsubmit="return validateForm()">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" id="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                    <div class="text-center mt-3">
                        <p>Already have an account? <a href="login.php" class="text-decoration-none">Login Now!</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Get all form elements
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const serverError = document.getElementById('serverError');
        const passwordError = document.getElementById('passwordError');

        // Clear server error when username is changed
        usernameInput.addEventListener('input', function() {
            serverError.style.display = 'none';
        });

        // Function to check password match
        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            if (password || confirmPassword) {
                if (password !== confirmPassword) {
                    passwordError.style.display = 'block';
                    return false;
                } else {
                    passwordError.style.display = 'none';
                    return true;
                }
            }
            passwordError.style.display = 'none';
            return true;
        }

        // Add event listeners for password fields
        passwordInput.addEventListener('input', checkPasswordMatch);
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);

        // Form validation before submit
        function validateForm() {
            return checkPasswordMatch();
        }
    </script>
</body>
</html>
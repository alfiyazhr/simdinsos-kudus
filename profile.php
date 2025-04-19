<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: auth/login.php');
    exit();
}

require 'config/db.php';

$username = $_SESSION['user'];
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Get user's current password from database
    $stmt = $pdo->prepare("SELECT password FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if (password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE username = :username");
            $stmt->execute([
                'password' => $hashed_password,
                'username' => $username
            ]);
            $message = "Password berhasil diubah!";
        } else {
            $error = "Password baru dan konfirmasi password tidak cocok!";
        }
    } else {
        $error = "Password saat ini tidak valid!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - DINSOS P3AP2KB</title>
    <link rel="icon" href="assets/logokudus.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            background-color: #004085;
            color: white;
        }

        .header-left {
            display: flex;
            align-items: center;
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

        .header-right a {
            color: white;
            text-decoration: none;
            font-size: 0.9rem;
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .header-right a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .header-right i {
            margin-right: 5px;
        }

        .dashboard-container {
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .card-header-custom {
            background-color: #004085;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 500;
            padding: 15px 20px;
        }

        .table-custom th {
            background-color: #004085;
            color: #ffffff;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .table-custom td {
            font-size: 0.9rem;
            vertical-align: middle;
        }

        .table-custom tbody tr:hover {
            background-color: #f8f9fa;
        }

        .status-select {
            width: 120px;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
            font-size: 0.9rem;
        }

        .card-custom {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .welcome-text {
            color: #004085;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
        }

        .year-badge {
            background-color: #004085;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-block;
            margin-left: 10px;
        }
        .filter-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-custom {
            background-color: #004085;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #002752;
            color: white;
        }
        .profile-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-icon {
            font-size: 5rem;
            color: #004085;
            margin-bottom: 1rem;
        }

        .profile-username {
            font-size: 1.5rem;
            font-weight: 600;
            color: #004085;
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .password-form {
            max-width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <img src="assets/logokudus.png" alt="Logo Kudus">
            <div>
                <h4>DINSOS P3AP2KB</h4>
                <h5>Kabupaten Kudus</h5>
            </div>
        </div>
        <div class="header-right">
            <a href="index.php">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="auth/logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <div class="dashboard-container">
        <h1 class="welcome-text">Profile</h1>

        <div class="card card-custom">
            <div class="card-header card-header-custom">
                <i class="fas fa-user me-2"></i>Informasi Profil
            </div>
            <div class="card-body">
                <div class="profile-section">
                    <i class="fas fa-user-circle profile-icon"></i>
                    <h2 class="profile-username"><?php echo htmlspecialchars($username); ?></h2>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <div class="password-form">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">
                                <i class="fas fa-lock me-2"></i>Password Saat Ini
                            </label>
                            <input type="password" class="form-control" id="current_password" 
                                   name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">
                                <i class="fas fa-key me-2"></i>Password Baru
                            </label>
                            <input type="password" class="form-control" id="new_password" 
                                   name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">
                                <i class="fas fa-check-double me-2"></i>Konfirmasi Password Baru
                            </label>
                            <input type="password" class="form-control" id="confirm_password" 
                                   name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-custom">
                            <i class="fas fa-save me-2"></i>Ubah Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
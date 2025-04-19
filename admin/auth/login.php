<?php
session_start();
require '../../config/db.php';

// Hardcode username dan password admin
$admin_username = 'dinsosp3ap2kb';
$admin_password = 'dinsos123'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $year = $_POST['year'];

    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin'] = $admin_username;
        $_SESSION['year'] = $year;
        header('Location: ../index.php'); // Pastikan path ini sesuai dengan lokasi file index admin
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - DINSOS P3AP2KB</title>
    <link rel="icon" href="../assets/logokudus.png" type="image/png">
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
            background-color: #004085; /* Warna header yang baru */
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
            background-color: #004085; /* Warna button login yang baru */
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
                <h3 class="text-center">Admin Login</h3>
                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $error; ?>
                    </div>
                <?php } ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" name="username" id="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="year" class="form-label">Tahun</label>
                        <select class="form-select" name="year" required>
                            <?php for ($tahun = 2000; $tahun <= 2100; $tahun++) { ?>
                                <option value="<?= $tahun; ?>" <?= ($tahun == 2025) ? 'selected' : ''; ?>>
                                    <?= $tahun; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

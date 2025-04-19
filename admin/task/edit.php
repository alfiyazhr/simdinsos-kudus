<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../auth/login.php');
    exit();
}

require '../../config/db.php';

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];
    
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE task_id = :task_id");
    $stmt->execute(['task_id' => $task_id]);
    $task = $stmt->fetch();
    
    if (!$task) {
        die('Task not found');
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $task_name = $_POST['task_name'];
        $task_link = $_POST['task_link'];
        $description = $_POST['description'];
        
        $stmt = $pdo->prepare("UPDATE tasks SET task_name = :task_name, task_link = :task_link, description = :description WHERE task_id = :task_id");
        $stmt->execute([
            'task_name' => $task_name,
            'task_link' => $task_link,
            'description' => $description,
            'task_id' => $task_id
        ]);
        
        header('Location: ../index.php');
        exit();
    }
} else {
    die('Task ID is required');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task - DINSOS P3AP2KB</title>
    <link rel="icon" href="../assets/logokudus.png" type="image/png">
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
            max-width: 800px;
            margin: 0 auto;
        }

        .card-custom {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card-header-custom {
            background-color: #004085;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 500;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0;
        }

        .welcome-text {
            color: #004085;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-label {
            color: #004085;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .form-control:focus {
            border-color: #004085;
            box-shadow: 0 0 0 0.2rem rgba(0, 64, 133, 0.25);
        }

        .btn-custom {
            background-color: #004085;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #002752;
            color: white;
        }

        .btn-secondary-custom {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background-color: #5a6268;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            <img src="../../assets/logokudus.png" alt="Logo Kudus">
            <div>
                <h4>DINSOS P3AP2KB</h4>
                <h5>Kabupaten Kudus</h5>
            </div>
        </div>
        <div class="header-right">
            <a href="#">
                <i class="fas fa-user"></i> Admin Dinsos P3AP2KB
            </a>
            <a href="../auth/logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <div class="dashboard-container">
        <h1 class="welcome-text">Edit Task</h1>

        <div class="card card-custom">
            <div class="card-header card-header-custom">
                <i class="fas fa-edit me-2"></i>Form Edit Task
            </div>
            <div class="card-body p-4">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="task_name" class="form-label">
                            <i class="fas fa-tasks me-2"></i>Nama Tugas
                        </label>
                        <input type="text" class="form-control" name="task_name" id="task_name" value="<?php echo htmlspecialchars($task['task_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="task_link" class="form-label">
                            <i class="fas fa-link me-2"></i>Link Tugas
                        </label>
                        <input type="text" class="form-control" name="task_link" id="task_link" value="<?php echo htmlspecialchars($task['task_link']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">
                            <i class="fas fa-align-left me-2"></i>Deskripsi
                        </label>
                        <textarea class="form-control" name="description" id="description" rows="4"><?php echo htmlspecialchars($task['description']); ?></textarea>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-custom">
                            <i class="fas fa-save me-2"></i>Update Task
                        </button>
                        <a href="../index.php" class="btn btn-secondary-custom">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
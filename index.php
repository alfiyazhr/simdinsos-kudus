<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: auth/login.php');
    exit();
}

require 'config/db.php';

$year = $_SESSION['year'];
$username = $_SESSION['user'];
$month = isset($_GET['month']) ? $_GET['month'] : '';

// Query berdasarkan tahun dan bulan jika ada filter
if ($month) {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE year = :year AND MONTH(created_at) = :month ORDER BY created_at DESC");
    $stmt->execute(['year' => $year, 'month' => $month]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE year = :year ORDER BY created_at DESC");
    $stmt->execute(['year' => $year]);
}
$tasks = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id = $_POST['task_id'];
    $status = $_POST['status'];

    if (in_array($status, ['pending', 'done'])) {
        $stmt = $pdo->prepare("UPDATE tasks SET status = :status WHERE task_id = :task_id");
        $stmt->execute(['status' => $status, 'task_id' => $task_id]);
    }

    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DINSOS P3AP2KB</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;

        }

        .welcome-text h1 {
            color: #004085;
            font-size: 1.8rem;
            font-weight: 600;
            text-align: center;
            margin: 0;
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
            <a href="profile.php">
                <i class="fas fa-user"></i> <?php echo htmlspecialchars($username); ?>
            </a>
            <a href="auth/logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="welcome-text">
            <h1>
                Dashboard
            </h1>
            <span class="year-badge">Tahun <?php echo htmlspecialchars($year); ?></span>
        </div>

        <div class="filter-section">
            <form method="GET" action="" class="d-flex align-items-center gap-3">
                <div class="flex-grow-1">
                    <label for="month" class="form-label">Filter Berdasarkan Bulan:</label>
                    <select name="month" id="month" class="form-select">
                        <option value="">Satu Tahun Penuh</option>
                        <?php
                        $months = [
                            '01' => 'Januari',
                            '02' => 'Februari',
                            '03' => 'Maret',
                            '04' => 'April',
                            '05' => 'Mei',
                            '06' => 'Juni',
                            '07' => 'Juli',
                            '08' => 'Agustus',
                            '09' => 'September',
                            '10' => 'Oktober',
                            '11' => 'November',
                            '12' => 'Desember'
                        ];
                        foreach ($months as $key => $month_name) {
                            $selected = ($month == $key) ? 'selected' : '';
                            echo "<option value='$key' $selected>$month_name</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="d-flex gap-2 align-items-end align-self-end">
                    <button type="submit" class="btn btn-custom">
                        <i class="fas fa-filter me-2"></i>Tampilkan
                    </button>
                </div>
            </form>
        </div>

        <div class="card card-custom">
            <div class="card-header card-header-custom">
                <i class="fas fa-tasks me-2"></i>Daftar Task
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-custom">
                        <thead>
                            <tr>
                                <th width="5%">No.</th>
                                <th width="15%">Tanggal</th>
                                <th width="25%">Nama Tugas</th>
                                <th width="35%">Deskripsi</th>
                                <th width="20%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($tasks as $task) {
                                $tanggal = date('d F Y', strtotime($task['created_at']));
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($tanggal); ?></td>
                                    <td>
                                        <a href="<?php echo htmlspecialchars($task['task_link']); ?>" target="_blank"
                                            class="text-decoration-none text-primary">
                                            <i class="fas fa-external-link-alt me-2"></i>
                                            <?php echo htmlspecialchars($task['task_name']); ?>
                                        </a>
                                    </td>
                                    <td><?php echo htmlspecialchars($task['description']); ?></td>
                                    <td>
                                        <form method="POST" action="">
                                            <input type="hidden" name="task_id" value="<?php echo $task['task_id']; ?>">
                                            <select name="status" onchange="this.form.submit()"
                                                class="status-select form-select">
                                                <option value="pending" <?php echo $task['status'] == 'pending' ? 'selected' : ''; ?>>
                                                    <i class="fas fa-clock"></i> Pending
                                                </option>
                                                <option value="done" <?php echo $task['status'] == 'done' ? 'selected' : ''; ?>>
                                                    <i class="fas fa-check"></i> Done
                                                </option>
                                                <?php if ($task['status'] == 'completed' || $task['status'] == 'rejected') { ?>
                                                    <option value="<?php echo $task['status']; ?>" selected>
                                                        <?php echo ucfirst($task['status']); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
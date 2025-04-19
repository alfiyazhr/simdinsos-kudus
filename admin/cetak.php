<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: auth/login.php');
    exit();
}

require_once '../config/db.php';
require_once '../libs/fpdf/fpdf.php';

// Ambil filter yang sama dengan index.php
$year = $_SESSION['year'] ?? date('Y');
$month = isset($_GET['month']) ? $_GET['month'] : '';

try {
    // Query yang sama dengan index.php untuk konsistensi data
    if ($month) {
        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE year = :year AND MONTH(created_at) = :month ORDER BY created_at DESC");
        $stmt->execute(['year' => $year, 'month' => $month]);
    } else {
        $stmt = $pdo->prepare("SELECT * FROM tasks WHERE year = :year ORDER BY created_at DESC");
        $stmt->execute(['year' => $year]);
    }
    $tasks = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching tasks: " . $e->getMessage());
}

class PDF extends FPDF {
    function Header() {
        // Logo (sesuaikan path)
        $this->Image('../assets/logokudus.png', 10, 10, 20);
        
        // Header Text
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(25); // Geser kanan setelah logo
        $this->Cell(0, 7, 'PEMERINTAH KABUPATEN KUDUS', 0, 1, 'C');
        $this->Cell(25);
        $this->Cell(0, 7, 'DINAS SOSIAL P3AP2KB', 0, 1, 'C');
        
        // Alamat (sesuaikan)
        $this->SetFont('Arial', '', 10);
        $this->Cell(25);
        $this->Cell(0, 5, 'Jl. Mejobo No. 50 Mlati Kudus, Jawa Tengah', 0, 1, 'C');
        
        // Garis pembatas
        $this->Ln(5);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Line(10, $this->GetY()+1, 200, $this->GetY()+1);
        $this->Ln(10);

        // Judul Laporan
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'LAPORAN DAFTAR TUGAS', 0, 1, 'C');
        
        // Periode
        $this->SetFont('Arial', '', 11);
        if (!empty($GLOBALS['month'])) {
            $bulan = [
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ];
            $this->Cell(0, 7, 'Periode: ' . $bulan[$GLOBALS['month']] . ' ' . $GLOBALS['year'], 0, 1, 'C');
        } else {
            $this->Cell(0, 7, 'Periode: Tahun ' . $GLOBALS['year'], 0, 1, 'C');
        }
        $this->Ln(5);

        // Header Tabel
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(10, 7, 'No.', 1, 0, 'C');
        $this->Cell(35, 7, 'Tanggal', 1, 0, 'C');
        $this->Cell(50, 7, 'Nama Tugas', 1, 0, 'C');
        $this->Cell(65, 7, 'Deskripsi', 1, 0, 'C');
        $this->Cell(30, 7, 'Status', 1, 1, 'C');
    }

    function Footer() {
        // Posisi 15 cm dari bawah
        $this->SetY(-60);
        
        $this->SetFont('Arial', '', 11);
        // Tempat dan Tanggal
        $this->Cell(120);
        $this->Cell(70, 10, 'Kudus, ' . date('d F Y'), 0, 1, 'C');
        $this->Cell(120);
        $this->Cell(70, 7, 'Kepala Dinas Sosial P3AP2KB', 0, 1, 'C');
        $this->Cell(120);
        $this->Cell(70, 7, 'Kabupaten Kudus', 0, 1, 'C');
        
        // Tempat tanda tangan
        $this->Ln(15);
        $this->Cell(120);
        $this->SetFont('Arial', 'BU', 11);
        $this->Cell(70, 7, $_POST['kepala_dinas'] ?? 'Alfiya Zahrotul Jannah', 0, 1, 'C');
        
        // Nomor Halaman
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Form untuk input nama kepala dinas
if (!isset($_POST['print'])) {
    ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan - Dinas Sosial P3AP2KB</title>
    <link rel="icon" href="assets/logokudus.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
    }

    .modal-custom {
        background: white;
        width: 400px;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .modal-custom h4 {
        color: #004085;
        font-weight: 600;
        font-size: 1.2rem;
        margin-bottom: 25px;
        text-align: center;
    }

    .form-label {
        display: block;
        text-align: center;
        color: #004085;
        font-size: 1rem;
        font-weight: 500;
        margin-bottom: 15px;
    }

    .form-control {
        border: 1px solid #dee2e6;
        padding: 12px 15px;
        font-size: 0.95rem;
        border-radius: 5px;
        margin-bottom: 25px;
        text-align: center;
    }

    .form-control:focus {
        border-color: #004085;
        box-shadow: 0 0 0 0.2rem rgba(0, 64, 133, 0.25);
    }

    .btn-custom {
        background-color: #004085;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 5px;
        font-size: 0.95rem;
        font-weight: 500;
        width: 100%;
        transition: all 0.3s ease;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-back {
        background-color: #6c757d;
    }

    .btn-custom:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .fa-print,
    .fa-arrow-left {
        margin-right: 10px;
        font-size: 1.1rem;
    }
    </style>
</head>

<body>
    <div class="overlay">
        <div class="modal-custom">
            <h4><i class="fas fa-file-pdf me-2"></i>Cetak Laporan Tugas</h4>
            <form method="POST" target="_blank">
                <div class="mb-4">
                    <div class="form-label">
                        Masukkan Nama Kepala Dinas
                    </div>
                    <input type="text" name="kepala_dinas" class="form-control" value=" " required>
                </div>
                <button type="submit" name="print" class="btn btn-custom">
                    <i class="fas fa-print"></i>
                    Cetak Laporan
                </button>
                <a href="../admin/index.php" class="btn btn-custom btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Dashboard
                </a>
            </form>
        </div>
    </div>
</body>

</html>
<?php
    exit();
}

// Buat PDF
$pdf = new PDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Isi tabel
if (empty($tasks)) {
    $pdf->Cell(0, 10, 'Tidak ada tugas pada periode ini.', 0, 1, 'C');
} else {
    $no = 1;
    foreach ($tasks as $task) {
        $pdf->Cell(10, 7, $no++, 1, 0, 'C');
        $pdf->Cell(35, 7, date('d/m/Y', strtotime($task['created_at'])), 1, 0, 'C');
        $pdf->Cell(50, 7, $task['task_name'], 1, 0, 'L');
        
        // Handle deskripsi yang panjang
        $desc = $task['description'];
        if (strlen($desc) > 50) {
            $desc = substr($desc, 0, 47) . '...';
        }
        $pdf->Cell(65, 7, $desc, 1, 0, 'L');
        $pdf->Cell(30, 7, $task['status'], 1, 1, 'C');
        
        // Cek jika perlu halaman baru
        if ($pdf->GetY() > 250) {
            $pdf->AddPage();
        }
    }
}

$pdf->Output('Laporan_Tugas.pdf', 'I');
?>
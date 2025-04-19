<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../auth/login.php');
    exit();
}

require '../../config/db.php';

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    $stmt = $pdo->prepare("DELETE FROM tasks WHERE task_id = :task_id");
    $stmt->execute(['task_id' => $task_id]);

    header('Location: ../index.php');
    exit();
} else {
    die('Task ID is required');
}
?>

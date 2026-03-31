<?php
// actions/fee_action.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Add Fee
if (isset($_POST['add_fee'])) {
    $student_id = $_POST['student_id'];
    $amount = $_POST['amount'];
    $month = $_POST['month'];
    $year = $_POST['year'];

    try {
        $stmt = $pdo->prepare("INSERT INTO fees (student_id, amount, month, year) VALUES (?, ?, ?, ?)");
        $stmt->execute([$student_id, $amount, $month, $year]);
        setFlashMessage('success', 'Fee added successfully');
    } catch (PDOException $e) {
        setFlashMessage('danger', 'Error adding fee: ' . $e->getMessage());
    }
    header("Location: ../admin/fees.php");
    exit();
}

// Mark as Paid
if (isset($_GET['pay'])) {
    $id = $_GET['pay'];
    try {
        $stmt = $pdo->prepare("UPDATE fees SET status = 'Paid', paid_date = NOW() WHERE id = ?");
        $stmt->execute([$id]);
        setFlashMessage('success', 'Fee marked as paid');
    } catch (PDOException $e) {
        setFlashMessage('danger', 'Error updating fee: ' . $e->getMessage());
    }
    header("Location: ../admin/fees.php");
    exit();
}

// Delete Fee
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM fees WHERE id = ?");
        $stmt->execute([$id]);
        setFlashMessage('success', 'Fee deleted successfully');
    } catch (PDOException $e) {
        setFlashMessage('danger', 'Error deleting fee: ' . $e->getMessage());
    }
    header("Location: ../admin/fees.php");
    exit();
}
?>

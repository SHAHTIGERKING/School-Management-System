<?php
// actions/attendance_action.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'teacher'])) {
    header("Location: ../public/login.php");
    exit();
}

if (isset($_POST['save_attendance'])) {
    $class_id = $_POST['class_id'];
    $date = $_POST['date']; // Y-m-d
    $attendance_data = $_POST['attendance']; // Array [student_id => status]

    try {
        $pdo->beginTransaction();

        // 1. Delete existing attendance for this class & date (simplest way to handle updates)
        $stmt = $pdo->prepare("DELETE FROM attendance WHERE class_id = ? AND date = ?");
        $stmt->execute([$class_id, $date]);

        // 2. Insert new records
        $stmt = $pdo->prepare("INSERT INTO attendance (student_id, class_id, date, status) VALUES (?, ?, ?, ?)");
        
        foreach ($attendance_data as $student_id => $status) {
            $stmt->execute([$student_id, $class_id, $date, $status]);
        }

        $pdo->commit();
        setFlashMessage('success', "Attendance saved for $date.");
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        setFlashMessage('danger', 'Error saving attendance: ' . $e->getMessage());
    }
    
    if ($_SESSION['role'] === 'teacher') {
        header("Location: ../teacher/attendance.php?class_id=$class_id&date=$date");
    } else {
        header("Location: ../admin/attendance.php?class_id=$class_id&date=$date");
    }
    exit();
}

if ($_SESSION['role'] === 'teacher') {
    header("Location: ../teacher/attendance.php");
} else {
    header("Location: ../admin/attendance.php");
}
exit();
?>

<?php
// actions/subject_action.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Add Subject
if (isset($_POST['add_subject'])) {
    $subject_name = sanitize($_POST['subject_name']);
    $subject_code = sanitize($_POST['subject_code']);

    if (!empty($subject_name) && !empty($subject_code)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO subjects (subject_name, subject_code) VALUES (?, ?)");
            $stmt->execute([$subject_name, $subject_code]);
            setFlashMessage('success', 'Subject added successfully');
        } catch (PDOException $e) {
            setFlashMessage('danger', 'Error adding subject: ' . $e->getMessage());
        }
    } else {
        setFlashMessage('danger', 'All fields are required');
    }
    header("Location: ../admin/subjects.php");
    exit();
}

// Delete Subject
if (isset($_GET['delete'])) {
    $id = sanitize($_GET['delete']);
    try {
        $stmt = $pdo->prepare("DELETE FROM subjects WHERE id = ?");
        $stmt->execute([$id]);
        setFlashMessage('success', 'Subject deleted successfully');
    } catch (PDOException $e) {
        setFlashMessage('danger', 'Error deleting subject: ' . $e->getMessage());
    }
    header("Location: ../admin/subjects.php");
    exit();
}

header("Location: ../admin/subjects.php");
exit();
?>

<?php
// actions/class_action.php
require_once '../config/db.php';
require_once '../includes/functions.php';

// Check Admin
// (In a real scenario, we should repeat session check here or use a helper, but for actions we check logic)
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Add Class
if (isset($_POST['add_class'])) {
    $class_name = sanitize($_POST['class_name']);
    $section = sanitize($_POST['section']);

    if (!empty($class_name) && !empty($section)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO classes (class_name, section) VALUES (?, ?)");
            $stmt->execute([$class_name, $section]);
            setFlashMessage('success', 'Class added successfully');
        } catch (PDOException $e) {
            setFlashMessage('danger', 'Error adding class: ' . $e->getMessage());
        }
    } else {
        setFlashMessage('danger', 'All fields are required');
    }
    header("Location: ../admin/classes.php");
    exit();
}

// Delete Class
if (isset($_GET['delete'])) {
    $id = sanitize($_GET['delete']);
    try {
        $stmt = $pdo->prepare("DELETE FROM classes WHERE id = ?");
        $stmt->execute([$id]);
        setFlashMessage('success', 'Class deleted successfully');
    } catch (PDOException $e) {
        setFlashMessage('danger', 'Error deleting class: ' . $e->getMessage());
    }
    header("Location: ../admin/classes.php");
    exit();
}

header("Location: ../admin/classes.php");
exit();
?>

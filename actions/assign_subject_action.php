<?php
// actions/assign_subject_action.php
require_once '../includes/functions.php';
require_once '../config/db.php';

checkRole('admin');

if (isset($_POST['assign_subject'])) {
    $class_id = $_POST['class_id'];
    $subject_id = $_POST['subject_id'];
    $teacher_id = $_POST['teacher_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO class_subjects (class_id, subject_id, teacher_id) VALUES (?, ?, ?)");
        $stmt->execute([$class_id, $subject_id, $teacher_id]);
        setFlashMessage("Subject assigned successfully!", "success");
    } catch (PDOException $e) {
        setFlashMessage("Error: " . $e->getMessage(), "danger");
    }
    header("Location: ../admin/assign_subjects.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM class_subjects WHERE id = ?");
        $stmt->execute([$id]);
        setFlashMessage("Assignment removed successfully!", "success");
    } catch (PDOException $e) {
        setFlashMessage("Error: " . $e->getMessage(), "danger");
    }
    header("Location: ../admin/assign_subjects.php");
    exit();
}

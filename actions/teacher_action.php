<?php
// actions/teacher_action.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Add Teacher
if (isset($_POST['add_teacher'])) {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $phone = sanitize($_POST['phone']);
    $qualification = sanitize($_POST['qualification']);
    $joining_date = sanitize($_POST['joining_date']);

    try {
        $pdo->beginTransaction();

        // 1. Create User
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'teacher')");
        $stmt->execute([$name, $email, $hashed]);
        $user_id = $pdo->lastInsertId();

        // 2. Create Teacher Profile
        $stmt = $pdo->prepare("INSERT INTO teachers (user_id, qualification, phone, joining_date) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $qualification, $phone, $joining_date]);

        $pdo->commit();
        setFlashMessage('success', 'Teacher added successfully');
    } catch (PDOException $e) {
        $pdo->rollBack();
        setFlashMessage('danger', 'Error adding teacher: ' . $e->getMessage());
    }
    header("Location: ../admin/teachers.php");
    exit();
}

// Delete Teacher (And User)
if (isset($_GET['delete'])) {
    $teacher_id = sanitize($_GET['delete']);
    try {
        // We need to get the user_id first to delete from users table
        // But ON DELETE CASCADE on teachers(user_id) FOREIGN KEY usually works the other way around.
        // Wait, schema:
        // teachers.user_id FK users.id ON DELETE CASCADE
        // So deleting USERS deletes TEACHERS.
        // But deleting TEACHERS does NOT delete USERS.
        // We should delete the USER.
        
        // Fetch user_id from teacher
        $stmt = $pdo->prepare("SELECT user_id FROM teachers WHERE id = ?");
        $stmt->execute([$teacher_id]);
        $teacher = $stmt->fetch();

        if ($teacher) {
            $user_id = $teacher['user_id'];
            // Delete User -> Cascades to Teacher
            $delStmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $delStmt->execute([$user_id]);
            setFlashMessage('success', 'Teacher deleted successfully');
        } else {
            setFlashMessage('danger', 'Teacher not found');
        }

    } catch (PDOException $e) {
        setFlashMessage('danger', 'Error deleting teacher: ' . $e->getMessage());
    }
    header("Location: ../admin/teachers.php");
    exit();
}
?>

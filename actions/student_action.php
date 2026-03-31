<?php
// actions/student_action.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../public/login.php");
    exit();
}

// Add Student
if (isset($_POST['add_student'])) {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $admission_no = sanitize($_POST['admission_no']);
    $class_id = sanitize($_POST['class_id']);
    $dob = sanitize($_POST['dob']);
    $gender = sanitize($_POST['gender']);
    $address = sanitize($_POST['address']);
    $phone = sanitize($_POST['phone']);

    if ($password !== $confirm_password) {
        setFlashMessage('danger', 'Passwords do not match');
        header("Location: ../admin/add_student.php");
        exit();
    }

    // Auto-generate Admission No
    if (empty($admission_no)) {
        $admission_no = 'ADM-' . date('Y') . '-' . mt_rand(1000, 9999);
    }

    try {
        $pdo->beginTransaction();

        // Check email
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            throw new Exception("Email already exists");
        }

        // Create User
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'student')");
        $stmt->execute([$name, $email, $hashed]);
        $user_id = $pdo->lastInsertId();

        // Create Student
        $stmt = $pdo->prepare("INSERT INTO students (user_id, admission_no, class_id, dob, gender, address, phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $admission_no, $class_id, $dob, $gender, $address, $phone]);

        $pdo->commit();
        setFlashMessage('success', 'Student added successfully');
        header("Location: ../admin/students.php");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        setFlashMessage('danger', 'Error adding student: ' . $e->getMessage());
        header("Location: ../admin/add_student.php");
        exit();
    }
}

// Edit Student
if (isset($_POST['edit_student'])) {
    $student_id = sanitize($_POST['student_id']);
    $user_id = sanitize($_POST['user_id']);
    
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $admission_no = sanitize($_POST['admission_no']);
    $class_id = sanitize($_POST['class_id']);
    $dob = sanitize($_POST['dob']);
    $gender = sanitize($_POST['gender']);
    $address = sanitize($_POST['address']);
    $phone = sanitize($_POST['phone']);

    if (!empty($password) && $password !== $confirm_password) {
        setFlashMessage('danger', 'Passwords do not match');
        header("Location: ../admin/edit_student.php?id=" . $student_id);
        exit();
    }

    // Auto-generate Admission No if empty
    if (empty($admission_no)) {
        $admission_no = 'ADM-' . date('Y') . '-' . mt_rand(1000, 9999);
    }

    try {
        $pdo->beginTransaction();

        // Check email for other users
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);
        if ($stmt->rowCount() > 0) {
            throw new Exception("Email already exists for another user");
        }

        // Update User
        if (!empty($password)) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
            $stmt->execute([$name, $email, $hashed, $user_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $stmt->execute([$name, $email, $user_id]);
        }

        // Update Student
        $stmt = $pdo->prepare("UPDATE students SET admission_no = ?, class_id = ?, dob = ?, gender = ?, address = ?, phone = ? WHERE id = ?");
        $stmt->execute([$admission_no, $class_id, $dob, $gender, $address, $phone, $student_id]);

        $pdo->commit();
        setFlashMessage('success', 'Student updated successfully');
        header("Location: ../admin/students.php");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        setFlashMessage('danger', 'Error updating student: ' . $e->getMessage());
        header("Location: ../admin/edit_student.php?id=" . $student_id);
        exit();
    }
}

// Delete Student
if (isset($_GET['delete'])) {
    $id = sanitize($_GET['delete']);
    // ID here is student ID, we need User ID to delete cascade (or consistent delete)
    // Actually our schema: students.user_id FK users.id ON DELETE CASCADE.
    // If we delete student, user remains? No, usually we want to delete the User account.
    // So we need to find the user_id from student_id.

    try {
        $stmt = $pdo->prepare("SELECT user_id FROM students WHERE id = ?");
        $stmt->execute([$id]);
        $student = $stmt->fetch();

        if ($student) {
            $user_id = $student['user_id'];
            $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$user_id]); // Will cascade delete student
            setFlashMessage('success', 'Student deleted successfully');
        } else {
             setFlashMessage('danger', 'Student not found');
        }
    } catch (PDOException $e) {
        setFlashMessage('danger', 'Delete failed: ' . $e->getMessage());
    }
    header("Location: ../admin/students.php");
    exit();
}
?>

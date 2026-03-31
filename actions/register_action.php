<?php
// actions/register_action.php
require_once '../config/db.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Sanitize Basic Inputs
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

    // 2. Validation
    if ($password !== $confirm_password) {
        setFlashMessage('danger', 'Passwords do not match.');
        header("Location: ../public/register.php");
        exit();
    }

    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            setFlashMessage('danger', 'Email already registered.');
            header("Location: ../public/register.php");
            exit();
        }

        // Auto-generate Admission No if empty
        if (empty($admission_no)) {
            $admission_no = 'ADM-' . date('Y') . '-' . mt_rand(1000, 9999);
        }

        // 3. File Upload
        $photo = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $uploaded = uploadFile($_FILES['photo']);
            if ($uploaded) {
                $photo = $uploaded;
            } else {
                // If upload checked failed (e.g. invalid type), deciding what to do.
                // User said "error nada" (give no error). 
                // We will just silently ignore the photo if it fails to upload, 
                // OR we can trust our new permissive uploadFile function.
                // Let's set a warning flash message but NOT stop registration?
                // Or just fail. The user said "fix it so it TAKES it".
                // Our new uploadFile is permissive. If it still fails, it's a real issue.
                // Let's assume it works now.
                setFlashMessage('warning', 'Invalid image format or upload failed. Registration continued without photo.');
                // Proceed without photo
                $photo = null;
            }
        }

        $pdo->beginTransaction();

        // 4. Create User (Role: Student)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'student')");
        $stmt->execute([$name, $email, $hashed_password]);
        $user_id = $pdo->lastInsertId();

        // 5. Create Student Record
        $stmt = $pdo->prepare("INSERT INTO students (user_id, admission_no, class_id, dob, gender, address, phone, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $admission_no, $class_id, $dob, $gender, $address, $phone, $photo]);

        $pdo->commit();

        setFlashMessage('success', 'Registration successful! You can now login.');
        header("Location: ../public/login.php");
        exit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        setFlashMessage('danger', 'Registration failed: ' . $e->getMessage());
        header("Location: ../public/register.php");
        exit();
    }

} else {
    header("Location: ../public/register.php");
    exit();
}
?>

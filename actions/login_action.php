<?php
// actions/login_action.php
require_once '../config/db.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        setFlashMessage('danger', 'Please fill in all fields.');
        header("Location: ../public/login.php");
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login Success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];

            setFlashMessage('success', 'Welcome back, ' . $user['name'] . '!');

            // Redirect based on role
            switch ($user['role']) {
                case 'admin':
                    header("Location: ../admin/index.php");
                    break;
                case 'teacher':
                    header("Location: ../teacher/index.php");
                    break;
                case 'student':
                    header("Location: ../student/index.php");
                    break;
                default:
                    header("Location: ../public/index.php");
            }
            exit();

        } else {
            setFlashMessage('danger', 'Invalid email or password.');
            header("Location: ../public/login.php");
            exit();
        }

    } catch (PDOException $e) {
        setFlashMessage('danger', 'Database error: ' . $e->getMessage());
        header("Location: ../public/login.php");
        exit();
    }
} else {
    header("Location: ../public/login.php");
    exit();
}
?>

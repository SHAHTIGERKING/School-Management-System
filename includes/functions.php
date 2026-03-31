<?php
// includes/functions.php

session_start();

/**
 * Sanitize input data
 */
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Redirect if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: ../public/login.php");
        exit();
    }
}

/**
 * Check for specific role authorization
 * @param string $role
 */
function checkRole($role) {
    if (!isLoggedIn() || $_SESSION['role'] !== $role) {
        // Redirect non-authorized users
        if (isLoggedIn()) {
            // Redirect to their respective dashboard based on their actual role
            switch ($_SESSION['role']) {
                case 'admin':
                    header("Location: ../admin/index.php");
                    break;
                case 'teacher':
                    header("Location: ../teacher/index.php"); // Create teacher folder later
                    break;
                case 'student':
                    header("Location: ../student/index.php"); // Create student folder later
                    break;
            }
        } else {
            header("Location: ../public/login.php");
        }
        exit();
    }
}

/**
 * Set Flash Message
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type, // success, danger, warning, info
        'message' => $message
    ];
}

/**
 * Display Flash Message
 */
function displayFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $type = $_SESSION['flash_message']['type'];
        $message = $_SESSION['flash_message']['message'];
        echo "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'>
                {$message}
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
        unset($_SESSION['flash_message']);
    }
}

/**
 * File Upload Helper
 * Returns filename on success, false on failure
 */
function uploadFile($file, $targetDir = "../uploads/") {
    // Generate unique filename to avoid conflicts and weird characters
    $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $newFileName = uniqid('img_', true) . '.' . $imageFileType;
    $targetFile = $targetDir . $newFileName;
    
    // Check if file is actually uploaded
    if(isset($file["tmp_name"]) && !empty($file["tmp_name"])) {
        
        // Allowed extensions (expanded)
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
        
        if (!in_array($imageFileType, $allowed)) {
            // If user really wants "ANY" file, we could remove this, but "Profile Photo" implies image.
            // For safety, we keep this but expanded. 
            // If strict requirement "no error ever", we could return false or just skip.
            // But let's assume they mean "any image type".
            return false;
        }

        // Try to move the file
        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return $newFileName;
        }
    }
    return false;
}
?>

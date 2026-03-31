<?php
// teacher/index.php
require_once '../config/db.php';
require_once '../includes/functions.php';

checkRole('teacher');

// Get Teacher ID
$teacher_id = 0;
// We need to fetch teacher profile using user_id
$stmt = $pdo->prepare("SELECT id, qualification FROM teachers WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$teacher = $stmt->fetch();
if($teacher) $teacher_id = $teacher['id'];

// Get assigned classes/subjects
// Assuming we have a class_subjects table (which we do, but haven't built UI to assign yet).
// For demo, we can just show generic info.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">SchoolSys - Teacher</a>
            <div class="d-flex">
                <a href="../actions/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-12 mb-4">
                <h2>Welcome, <?php echo $_SESSION['user_name']; ?></h2>
                <p class="text-muted">Teacher Dashboard</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow border-0">
                    <div class="card-body text-center">
                        <i class="fas fa-chalkboard fa-3x text-success mb-3"></i>
                        <h5>My Classes</h5>
                        <p>View the classes and subjects you are assigned to.</p>
                        <a href="classes.php" class="btn btn-success btn-sm">View Classes</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow border-0">
                    <div class="card-body text-center">
                        <i class="fas fa-user-check fa-3x text-primary mb-3"></i>
                        <h5>Mark Attendance</h5>
                        <p>Mark daily attendance for your students.</p>
                        <a href="attendance.php" class="btn btn-primary btn-sm">Mark Attendance</a> 
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow border-0">
                    <div class="card-body text-center">
                        <i class="fas fa-file-alt fa-3x text-warning mb-3"></i>
                        <h5>Results</h5>
                        <p>Upload exam marks for your subjects.</p>
                        <a href="results.php" class="btn btn-warning btn-sm text-white">Upload Results</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

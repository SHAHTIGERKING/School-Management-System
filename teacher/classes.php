<?php
// teacher/classes.php
require_once '../config/db.php';
require_once '../includes/functions.php';

checkRole('teacher');

// Get Teacher ID
$teacher_id = 0;
// We need to fetch teacher profile using user_id
$stmt = $pdo->prepare("SELECT id FROM teachers WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$teacher = $stmt->fetch();
if($teacher) $teacher_id = $teacher['id'];

// Fetch classes assigned to this teacher
$sql = "SELECT c.id, c.class_name, c.section, s.subject_name 
        FROM class_subjects cs 
        JOIN classes c ON cs.class_id = c.id 
        JOIN subjects s ON cs.subject_id = s.id 
        WHERE cs.teacher_id = ?
        ORDER BY c.class_name ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$teacher_id]);
$assignments = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assigned Classes - Teacher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">SchoolSys - Teacher</a>
            <div class="d-flex">
                <a href="index.php" class="btn btn-light btn-sm me-2">Dashboard</a>
                <a href="../actions/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">My Assigned Classes & Subjects</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Subject</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($assignments) > 0): ?>
                                <?php foreach($assignments as $a): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($a['class_name']); ?></td>
                                    <td><?php echo htmlspecialchars($a['section']); ?></td>
                                    <td><?php echo htmlspecialchars($a['subject_name']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center">No assignments found</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

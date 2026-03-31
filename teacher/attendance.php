<?php
// teacher/attendance.php
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

// Fetch distinct classes assigned to this teacher for dropdown
$sql = "SELECT DISTINCT c.id, c.class_name, c.section 
        FROM class_subjects cs 
        JOIN classes c ON cs.class_id = c.id 
        WHERE cs.teacher_id = ?
        ORDER BY c.class_name ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$teacher_id]);
$classes = $stmt->fetchAll();

$selected_class_id = isset($_GET['class_id']) ? $_GET['class_id'] : '';
$selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$students = [];

if ($selected_class_id) {
    // Fetch students in the selected class
    $stmt = $pdo->prepare("SELECT s.id, s.admission_no, u.name 
                           FROM students s 
                           JOIN users u ON s.user_id = u.id 
                           WHERE s.class_id = ? 
                           ORDER BY u.name ASC");
    $stmt->execute([$selected_class_id]);
    $students = $stmt->fetchAll();

    // Re-fetch formatted by student_id
    $stmt = $pdo->prepare("SELECT student_id, status FROM attendance WHERE class_id = ? AND date = ?");
    $stmt->execute([$selected_class_id, $selected_date]);
    $attendance_map = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mark Attendance - Teacher</title>
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
            <h1 class="h2">Mark Attendance</h1>
        </div>

        <?php displayFlashMessage(); ?>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Select Assigned Class</label>
                        <select name="class_id" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Select Class --</option>
                            <?php foreach($classes as $c): ?>
                                <option value="<?php echo $c['id']; ?>" <?php echo $selected_class_id == $c['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($c['class_name'] . ' ' . $c['section']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date</label>
                        <input type="date" name="date" class="form-control" value="<?php echo $selected_date; ?>" onchange="this.form.submit()">
                    </div>
                </form>
            </div>
        </div>

        <?php if ($selected_class_id && count($students) > 0): ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Mark Attendance for <?php echo $selected_date; ?></h6>
            </div>
            <div class="card-body">
                <form action="../actions/attendance_action.php" method="POST">
                    <input type="hidden" name="class_id" value="<?php echo $selected_class_id; ?>">
                    <input type="hidden" name="date" value="<?php echo $selected_date; ?>">
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th class="text-center">Present</th>
                                    <th class="text-center">Absent</th>
                                    <th class="text-center">Late</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($students as $student): ?>
                                <?php 
                                    $status = isset($attendance_map[$student['id']]) ? $attendance_map[$student['id']] : 'Present'; // Default to Present
                                ?>
                                <tr>
                                    <td><?php echo $student['admission_no']; ?></td>
                                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                                    <td class="text-center">
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="radio" name="attendance[<?php echo $student['id']; ?>]" value="Present" <?php echo $status == 'Present' ? 'checked' : ''; ?>>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="radio" name="attendance[<?php echo $student['id']; ?>]" value="Absent" <?php echo $status == 'Absent' ? 'checked' : ''; ?>>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-inline-block">
                                            <input class="form-check-input" type="radio" name="attendance[<?php echo $student['id']; ?>]" value="Late" <?php echo $status == 'Late' ? 'checked' : ''; ?>>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" name="save_attendance" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Save Attendance
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php elseif ($selected_class_id): ?>
            <div class="alert alert-info">No students found in this class.</div>
        <?php endif; ?>
    </div>
</body>
</html>

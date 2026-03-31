<?php
// teacher/results.php
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


// Fetch classes and subjects assigned to this teacher
$sql = "SELECT cs.id as assignment_id, c.id as class_id, c.class_name, c.section, s.id as subject_id, s.subject_name 
        FROM class_subjects cs 
        JOIN classes c ON cs.class_id = c.id 
        JOIN subjects s ON cs.subject_id = s.id 
        WHERE cs.teacher_id = ?
        ORDER BY c.class_name ASC, s.subject_name ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$teacher_id]);
$assignments = $stmt->fetchAll();

// We can build a unique list of classes and subjects for filtering depending on preference,
// but since this combines both, a single select of "Class - Subject" is easiest.

$assignment_id = isset($_GET['assignment']) ? $_GET['assignment'] : '';
$exam_name = isset($_GET['exam_name']) ? $_GET['exam_name'] : 'Midterm';

$students = [];
$class_id = '';
$subject_id = '';

if ($assignment_id) {
    // extract class and subject from the selected assignment
    foreach ($assignments as $a) {
        if ($a['assignment_id'] == $assignment_id) {
            $class_id = $a['class_id'];
            $subject_id = $a['subject_id'];
            break;
        }
    }
}

if ($class_id && $subject_id) {
    // Fetch students
    $stmt = $pdo->prepare("SELECT s.id, s.admission_no, u.name 
                           FROM students s 
                           JOIN users u ON s.user_id = u.id 
                           WHERE s.class_id = ? 
                           ORDER BY u.name ASC");
    $stmt->execute([$class_id]);
    $students = $stmt->fetchAll();

    // Fetch existing results
    $stmt = $pdo->prepare("SELECT student_id, marks_obtained FROM results WHERE subject_id = ? AND exam_name = ? AND student_id IN (SELECT id FROM students WHERE class_id = ?)");
    $stmt->execute([$subject_id, $exam_name, $class_id]);
    $results_map = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Results - Teacher</title>
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
            <h1 class="h2">Upload Results</h1>
        </div>

        <?php displayFlashMessage(); ?>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="" method="GET" class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Select Class and Subject</label>
                        <select name="assignment" class="form-select" required>
                            <option value="">-- Select Assigned Subject --</option>
                            <?php foreach($assignments as $a): ?>
                                <option value="<?php echo $a['assignment_id']; ?>" <?php echo $assignment_id == $a['assignment_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($a['class_name'] . ' ' . $a['section'] . ' - ' . $a['subject_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                     <div class="col-md-4">
                        <label class="form-label">Exam Name</label>
                        <select name="exam_name" class="form-select">
                            <option value="Midterm" <?php echo $exam_name == 'Midterm' ? 'selected' : ''; ?>>Midterm</option>
                            <option value="Final" <?php echo $exam_name == 'Final' ? 'selected' : ''; ?>>Final</option>
                            <option value="UnitTest" <?php echo $exam_name == 'UnitTest' ? 'selected' : ''; ?>>Unit Test</option>
                        </select>
                    </div>
                    <div class="col-md-3 align-self-end">
                        <button type="submit" class="btn btn-success w-100">Load Students</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($class_id && $subject_id && count($students) > 0): ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Enter Marks for <?php echo $exam_name; ?></h6>
            </div>
            <div class="card-body">
                <form action="../actions/result_action.php" method="POST">
                     <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                     <input type="hidden" name="subject_id" value="<?php echo $subject_id; ?>">
                     <input type="hidden" name="exam_name" value="<?php echo $exam_name; ?>">
                     <input type="hidden" name="assignment" value="<?php echo $assignment_id; ?>"> 

                     <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Marks Obtained (Out of 100)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($students as $student): ?>
                                <tr>
                                    <td><?php echo $student['admission_no']; ?></td>
                                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                                    <td>
                                        <input type="number" name="marks[<?php echo $student['id']; ?>]" class="form-control" step="0.01" min="0" max="100" 
                                        value="<?php echo isset($results_map[$student['id']]) ? $results_map[$student['id']] : ''; ?>" placeholder="Enter Marks">
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                     <div class="text-end mt-3">
                        <button type="submit" name="save_results" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Save Results
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php elseif ($assignment_id): ?>
             <div class="alert alert-info">No students found or please select class/subject.</div>
        <?php endif; ?>
    </div>
</body>
</html>

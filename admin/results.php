<?php
// admin/results.php
require_once 'includes/header.php';

// Fetch classes and subjects
$classes = $pdo->query("SELECT * FROM classes")->fetchAll();
$subjects = $pdo->query("SELECT * FROM subjects")->fetchAll();

$class_id = isset($_GET['class_id']) ? $_GET['class_id'] : '';
$subject_id = isset($_GET['subject_id']) ? $_GET['subject_id'] : '';
$exam_name = isset($_GET['exam_name']) ? $_GET['exam_name'] : 'Midterm';

$students = [];

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

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Results Management</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Class</label>
                <select name="class_id" class="form-select" required>
                    <option value="">Select Class</option>
                    <?php foreach($classes as $c): ?>
                        <option value="<?php echo $c['id']; ?>" <?php echo $class_id == $c['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($c['class_name'] . ' ' . $c['section']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Subject</label>
                <select name="subject_id" class="form-select" required>
                     <option value="">Select Subject</option>
                    <?php foreach($subjects as $s): ?>
                        <option value="<?php echo $s['id']; ?>" <?php echo $subject_id == $s['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($s['subject_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
             <div class="col-md-3">
                <label class="form-label">Exam Name</label>
                <select name="exam_name" class="form-select">
                    <option value="Midterm" <?php echo $exam_name == 'Midterm' ? 'selected' : ''; ?>>Midterm</option>
                    <option value="Final" <?php echo $exam_name == 'Final' ? 'selected' : ''; ?>>Final</option>
                    <option value="UnitTest" <?php echo $exam_name == 'UnitTest' ? 'selected' : ''; ?>>Unit Test</option>
                </select>
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary w-100">Load Students</button>
            </div>
        </form>
    </div>
</div>

<?php if ($class_id && $subject_id && count($students) > 0): ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Enter Marks for <?php echo $exam_name; ?></h6>
    </div>
    <div class="card-body">
        <form action="../actions/result_action.php" method="POST">
             <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
             <input type="hidden" name="subject_id" value="<?php echo $subject_id; ?>">
             <input type="hidden" name="exam_name" value="<?php echo $exam_name; ?>">

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
<?php elseif ($class_id): ?>
     <div class="alert alert-info">No students found or please select class/subject.</div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>

<?php
// admin/assign_subjects.php
require_once 'includes/header.php';

// Fetch classes, subjects, and teachers for dropdowns
$classes = $pdo->query("SELECT * FROM classes ORDER BY class_name ASC")->fetchAll();
$subjects = $pdo->query("SELECT * FROM subjects ORDER BY subject_name ASC")->fetchAll();
$teachers = $pdo->query("SELECT u.name, t.id FROM teachers t JOIN users u ON t.user_id = u.id ORDER BY u.name ASC")->fetchAll();

// Fetch existing assignments
$sql = "SELECT cs.id, c.class_name, c.section, s.subject_name, u.name as teacher_name 
        FROM class_subjects cs 
        JOIN classes c ON cs.class_id = c.id 
        JOIN subjects s ON cs.subject_id = s.id 
        JOIN teachers t ON cs.teacher_id = t.id 
        JOIN users u ON t.user_id = u.id 
        ORDER BY c.class_name ASC";
$assignments = $pdo->query($sql)->fetchAll();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Assign Subjects & Teachers</h1>
    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal">
        <i class="fas fa-plus"></i> New Assignment
    </button>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Class</th>
                        <th>Subject</th>
                        <th>Assigned Teacher</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($assignments) > 0): ?>
                        <?php foreach($assignments as $a): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($a['class_name'] . ' ' . $a['section']); ?></td>
                            <td><?php echo htmlspecialchars($a['subject_name']); ?></td>
                            <td><?php echo htmlspecialchars($a['teacher_name']); ?></td>
                            <td>
                                <a href="../actions/assign_subject_action.php?delete=<?php echo $a['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">
                                    <i class="fas fa-trash"></i> Remove
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center">No assignments found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Assign Modal -->
<div class="modal fade" id="assignModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="../actions/assign_subject_action.php" method="POST">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Subject to Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Select Class</label>
                    <select name="class_id" class="form-select" required>
                        <option value="">-- Choose Class --</option>
                        <?php foreach($classes as $c): ?>
                            <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['class_name'] . ' ' . $c['section']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Select Subject</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">-- Choose Subject --</option>
                        <?php foreach($subjects as $s): ?>
                            <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['subject_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Select Teacher</label>
                    <select name="teacher_id" class="form-select" required>
                        <option value="">-- Choose Teacher --</option>
                        <?php foreach($teachers as $t): ?>
                            <option value="<?php echo $t['id']; ?>"><?php echo htmlspecialchars($t['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="assign_subject" class="btn btn-primary">Save Assignment</button>
            </div>
        </div>
    </form>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>

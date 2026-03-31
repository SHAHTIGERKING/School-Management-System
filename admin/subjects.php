<?php
// admin/subjects.php
require_once 'includes/header.php';

// Fetch all subjects
$stmt = $pdo->query("SELECT * FROM subjects ORDER BY subject_name ASC");
$subjects = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Subjects Management</h1>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
        <i class="fas fa-plus"></i> Add New Subject
    </button>
</div>

<!-- Subjects Table -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Subject Name</th>
                        <th>Subject Code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($subjects) > 0): ?>
                        <?php foreach($subjects as $subject): ?>
                        <tr>
                            <td><?php echo $subject['id']; ?></td>
                            <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                            <td><?php echo htmlspecialchars($subject['subject_code']); ?></td>
                            <td>
                                <a href="../actions/subject_action.php?delete=<?php echo $subject['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center">No subjects found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="../actions/subject_action.php" method="POST">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubjectModalLabel">Add New Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="subject_name" class="form-label">Subject Name</label>
                    <input type="text" class="form-control" id="subject_name" name="subject_name" required>
                </div>
                <div class="mb-3">
                    <label for="subject_code" class="form-label">Subject Code</label>
                    <input type="text" class="form-control" id="subject_code" name="subject_code" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="add_subject" class="btn btn-primary">Save Subject</button>
            </div>
        </div>
    </form>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>

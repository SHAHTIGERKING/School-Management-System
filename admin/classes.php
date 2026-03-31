<?php
// admin/classes.php
require_once 'includes/header.php';

// Fetch all classes
$stmt = $pdo->query("SELECT * FROM classes ORDER BY class_name ASC");
$classes = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Classes Management</h1>
    <?php if($_SESSION['role'] == 'admin'): ?>
    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addClassModal">
        <i class="fas fa-plus"></i> Add New Class
    </button>
    <?php endif; ?>
</div>

<!-- Classes Table -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Class Name</th>
                        <th>Section</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($classes) > 0): ?>
                        <?php foreach($classes as $class): ?>
                        <tr>
                            <td><?php echo $class['id']; ?></td>
                            <td><?php echo htmlspecialchars($class['class_name']); ?></td>
                            <td><?php echo htmlspecialchars($class['section']); ?></td>
                            <td>
                                <?php if($_SESSION['role'] == 'admin'): ?>
                                <a href="../actions/class_action.php?delete=<?php echo $class['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure? This will delete all students in this class!');">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                                <?php else: ?>
                                <span class="badge bg-secondary">View Only</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center">No classes found</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Class Modal -->
<div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="addClassModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="../actions/class_action.php" method="POST">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClassModalLabel">Add New Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="class_name" class="form-label">Class Name</label>
                    <input type="text" class="form-control" id="class_name" name="class_name" placeholder="e.g. Class 10" required>
                </div>
                <div class="mb-3">
                    <label for="section" class="form-label">Section</label>
                    <input type="text" class="form-control" id="section" name="section" placeholder="e.g. A" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="add_class" class="btn btn-primary">Save Class</button>
            </div>
        </div>
    </form>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>

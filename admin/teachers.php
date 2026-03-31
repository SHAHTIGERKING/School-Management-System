<?php
// admin/teachers.php
require_once 'includes/header.php';

// Fetch all teachers
$sql = "SELECT t.*, u.name, u.email FROM teachers t JOIN users u ON t.user_id = u.id ORDER BY u.name ASC";
$stmt = $pdo->query($sql);
$teachers = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Teachers Management</h1>
    <a href="add_teacher.php" class="btn btn-sm btn-primary">
        <i class="fas fa-plus"></i> Add New Teacher
    </a>
</div>

<!-- Teachers Table -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Qualification</th>
                        <th>Joining Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($teachers as $teacher): ?>
                    <tr>
                        <td><?php echo $teacher['id']; ?></td>
                        <td><?php echo htmlspecialchars($teacher['name']); ?></td>
                        <td><?php echo htmlspecialchars($teacher['email']); ?></td>
                        <td><?php echo htmlspecialchars($teacher['phone']); ?></td>
                        <td><?php echo htmlspecialchars($teacher['qualification']); ?></td>
                        <td><?php echo htmlspecialchars($teacher['joining_date']); ?></td>
                        <td>
                            <a href="../actions/teacher_action.php?delete=<?php echo $teacher['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure? This will also delete the user account related to this teacher!');">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

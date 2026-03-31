<?php
// admin/edit_student.php
require_once 'includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: students.php");
    exit();
}

$id = sanitize($_GET['id']);

// Fetch student details
$stmt = $pdo->prepare("SELECT s.*, u.name, u.email FROM students s JOIN users u ON s.user_id = u.id WHERE s.id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) {
    setFlashMessage('danger', 'Student not found.');
    header("Location: students.php");
    exit();
}

// Fetch classes
$stmt = $pdo->query("SELECT * FROM classes ORDER BY class_name ASC");
$classes = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Student</h1>
    <a href="students.php" class="btn btn-sm btn-secondary"> <i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="../actions/student_action.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
            <input type="hidden" name="user_id" value="<?php echo $student['user_id']; ?>">
            
            <h5 class="mb-3 text-primary">Login Details</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($student['name'] ?? ''); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($student['email'] ?? ''); ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Leave blank to keep current password">
                </div>
                 <div class="col-md-6">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="confirm_password" placeholder="Leave blank to keep current password">
                </div>
            </div>

            <hr>
            <h5 class="mb-3 text-primary">Student Details</h5>
            
            <div class="row mb-3">
                <div class="col-md-4">
                     <label class="form-label">Admission No (Optional)</label>
                     <input type="text" class="form-control" name="admission_no" value="<?php echo htmlspecialchars($student['admission_no'] ?? ''); ?>" placeholder="Auto-generated if blank">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Class</label>
                    <select class="form-select" name="class_id" required>
                        <option value="">Select Class</option>
                        <?php foreach($classes as $c): ?>
                            <option value="<?php echo $c['id']; ?>" <?php echo ($c['id'] == $student['class_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['class_name'] . ' ' . $c['section']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                 <div class="col-md-4">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" name="dob" value="<?php echo htmlspecialchars($student['dob'] ?? ''); ?>">
                </div>
            </div>

            <div class="row mb-3">
                 <div class="col-md-6">
                    <label class="form-label">Gender</label>
                    <select class="form-select" name="gender">
                        <option value="Male" <?php echo ($student['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($student['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo ($student['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($student['phone'] ?? ''); ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea class="form-control" name="address" rows="2"><?php echo htmlspecialchars($student['address'] ?? ''); ?></textarea>
            </div>
            
            <button type="submit" name="edit_student" class="btn btn-primary">Update Student</button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

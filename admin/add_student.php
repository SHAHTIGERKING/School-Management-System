<?php
// admin/add_student.php
require_once 'includes/header.php';

// Fetch classes
$stmt = $pdo->query("SELECT * FROM classes ORDER BY class_name ASC");
$classes = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add New Student</h1>
    <a href="students.php" class="btn btn-sm btn-secondary"> <i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="../actions/student_action.php" method="POST" enctype="multipart/form-data">
            <h5 class="mb-3 text-primary">Login Details</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                 <div class="col-md-6">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" name="confirm_password" required>
                </div>
            </div>

            <hr>
            <h5 class="mb-3 text-primary">Student Details</h5>
            
            <div class="row mb-3">
                <div class="col-md-4">
                     <label class="form-label">Admission No (Optional)</label>
                     <input type="text" class="form-control" name="admission_no" placeholder="Auto-generated if blank">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Class</label>
                    <select class="form-select" name="class_id" required>
                        <option value="">Select Class</option>
                        <?php foreach($classes as $c): ?>
                            <option value="<?php echo $c['id']; ?>"><?php echo $c['class_name'] . ' ' . $c['section']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                 <div class="col-md-4">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" name="dob">
                </div>
            </div>

            <div class="row mb-3">
                 <div class="col-md-6">
                    <label class="form-label">Gender</label>
                    <select class="form-select" name="gender">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea class="form-control" name="address" rows="2"></textarea>
            </div>
            
            <button type="submit" name="add_student" class="btn btn-primary">Add Student</button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

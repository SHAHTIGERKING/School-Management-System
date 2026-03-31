<?php
// admin/add_teacher.php
require_once 'includes/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add New Teacher</h1>
    <a href="teachers.php" class="btn btn-sm btn-secondary"> <i class="fas fa-arrow-left"></i> Back</a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <form action="../actions/teacher_action.php" method="POST">
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
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Qualification</label>
                    <input type="text" class="form-control" name="qualification">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Joining Date</label>
                    <input type="date" class="form-control" name="joining_date">
                </div>
            </div>

            <button type="submit" name="add_teacher" class="btn btn-primary">Add Teacher</button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

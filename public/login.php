<?php
require_once '../includes/functions.php';

// If already logged in, redirect based on role
if (isset($_SESSION['user_id'])) {
    checkRole($_SESSION['role']); 
    // If checkRole doesn't redirect (e.g. same role), manually redirect
    switch ($_SESSION['role']) {
        case 'admin': header("Location: ../admin/index.php"); break;
        case 'teacher': header("Location: ../teacher/index.php"); break;
        case 'student': header("Location: ../student/index.php"); break;
    }
    exit();
}

require_once '../includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4"><i class="fas fa-sign-in-alt text-primary me-2"></i>Login</h2>
                    
                    <form action="../actions/login_action.php" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your email">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your password">
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                    
                    <div class="mt-4 text-center">
                        <p>Don't have an account? <a href="register.php">Register (Students)</a></p>
                        <p class="small text-muted">For Teachers/Admins, please contact the administrator.</p>
                    </div>

                    <div class="alert alert-info mt-3">
                        <h6 class="alert-heading"><i class="fas fa-info-circle me-1"></i> Admin Credentials:</h6>
                        <hr class="my-2">
                        <p class="mb-1 small"><strong>Email:</strong> shahzaib@gmail.com</p>
                        <p class="mb-0 small"><strong>Password:</strong> shahzaib123</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>

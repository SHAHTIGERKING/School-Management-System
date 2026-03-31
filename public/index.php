<?php
require_once '../includes/functions.php';
require_once '../includes/header.php';
?>

<!-- Hero Section -->
<header class="hero-section text-white text-center py-5 mb-5" style="background-color: #343a40;"> 

    <div class="container">
        <h1 class="display-3 fw-bold">Empowering Education with Technology</h1>
        <p class="lead mb-4">Streamline your school management with our advanced, user-friendly platform.</p>
        <?php if(!isLoggedIn()): ?>
            <a href="register.php" class="btn btn-primary btn-lg px-4 me-2">Get Started</a>
            <a href="about.php" class="btn btn-outline-light btn-lg px-4">Learn More</a>
        <?php else: ?>
            <a href="../admin/index.php" class="btn btn-light btn-lg px-4">Go to Dashboard</a>
        <?php endif; ?>
    </div>
</header>

<!-- Features Section -->
<section class="container mb-5">
    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="card h-100 p-4">
                <div class="card-body">
                    <i class="fas fa-user-graduate feature-icon"></i>
                    <h3 class="card-title">Student Management</h3>
                    <p class="card-text">Efficiently manage student records, profiles, admissions, and communication in one centralized location.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 p-4">
                <div class="card-body">
                    <i class="fas fa-calendar-check feature-icon"></i>
                    <h3 class="card-title">Attendance Tracking</h3>
                    <p class="card-text">Easily track daily attendance for students and staff, generate reports, and ensure compliance.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 p-4">
                <div class="card-body">
                    <i class="fas fa-chart-line feature-icon"></i>
                    <h3 class="card-title">Result Analysis</h3>
                    <p class="card-text">Analyze student performance, generate detailed grade reports, and identify areas for improvement.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>

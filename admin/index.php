<?php
// admin/index.php
require_once 'includes/header.php';

// Fetch Statistics
// Total Students
$stmt = $pdo->query("SELECT COUNT(*) FROM students");
$total_students = $stmt->fetchColumn();

// Total Teachers
$stmt = $pdo->query("SELECT COUNT(*) FROM teachers");
$total_teachers = $stmt->fetchColumn();

// Total Classes
$stmt = $pdo->query("SELECT COUNT(*) FROM classes");
$total_classes = $stmt->fetchColumn();

// Attendance Today (Students Present) // Assuming 'Present' is the status string
$today = date('Y-m-d');
$stmt = $pdo->prepare("SELECT COUNT(*) FROM attendance WHERE date = ? AND status = 'Present'");
$stmt->execute([$today]);
$present_today = $stmt->fetchColumn();

?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
        </div>
    </div>
</div>

<div class="row">
    <!-- Card 1: Students -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 border-0 bg-primary text-white">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white-50 text-uppercase mb-1">Total Students</div>
                        <div class="h5 mb-0 font-weight-bold text-white"><?php echo $total_students; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-graduate fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Teachers -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2 border-0 bg-success text-white">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white-50 text-uppercase mb-1">Total Teachers</div>
                        <div class="h5 mb-0 font-weight-bold text-white"><?php echo $total_teachers; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chalkboard-teacher fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Classes -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2 border-0 bg-warning text-white">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white-50 text-uppercase mb-1">Total Classes</div>
                        <div class="h5 mb-0 font-weight-bold text-white"><?php echo $total_classes; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chalkboard fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: Attendance -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2 border-0 bg-info text-white">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white-50 text-uppercase mb-1">Attendance (Today)</div>
                        <div class="h5 mb-0 font-weight-bold text-white"><?php echo $present_today; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-check fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities / Quick Links Section Example -->
<div class="row mt-4">
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <a href="students.php" class="btn btn-primary mb-2">Manage Students</a>
                <a href="teachers.php" class="btn btn-success mb-2">Manage Teachers</a>
                <a href="attendance.php" class="btn btn-info mb-2 text-white">Mark Attendance</a>
                <a href="results.php" class="btn btn-warning mb-2 text-dark">Upload Results</a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">System Info</h6>
            </div>
            <div class="card-body">
                <p><strong>System Status:</strong> <span class="badge bg-success">Online</span></p>
                <p><strong>Database:</strong> Connected</p>
                <p><strong>Current Date:</strong> <?php echo date('Y-m-d'); ?></p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

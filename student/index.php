<?php
// student/index.php
require_once '../config/db.php';
require_once '../includes/functions.php';

checkRole('student');

// Fetch Student Profile
$stmt = $pdo->prepare("SELECT s.*, c.class_name, c.section 
                       FROM students s 
                       LEFT JOIN classes c ON s.class_id = c.id 
                       WHERE s.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$student = $stmt->fetch();

$student_id = $student['id'];

// Fetch Results
$results = $pdo->prepare("SELECT r.*, s.subject_name 
                          FROM results r 
                          JOIN subjects s ON r.subject_id = s.id 
                          WHERE r.student_id = ? 
                          ORDER BY r.created_at DESC");
$results->execute([$student_id]);
$result_rows = $results->fetchAll();

// Fetch Fees
$fees = $pdo->prepare("SELECT * FROM fees WHERE student_id = ? ORDER BY id DESC");
$fees->execute([$student_id]);
$fee_rows = $fees->fetchAll();

// Fetch Attendance
$attendance = $pdo->prepare("SELECT * FROM attendance WHERE student_id = ? ORDER BY date DESC LIMIT 30");
$attendance->execute([$student_id]);
$attendance_rows = $attendance->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .navbar, .btn, .text-end, .print-hide { display: none !important; }
            .container { width: 100% !important; max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
            .card { border: 1px solid #dee2e6 !important; box-shadow: none !important; margin-bottom: 20px !important; }
            body { background-color: white !important; }
            .section-to-print { display: block !important; }
            .section-to-hide { display: none !important; }
        }
    </style>
    <script>
        function printSection(sectionId) {
            const allSections = ['results-card', 'fees-card', 'attendance-card'];
            allSections.forEach(id => {
                const el = document.getElementById(id).closest('.col-md-6');
                if (id === sectionId) {
                    el.classList.add('section-to-print');
                    el.classList.remove('section-to-hide');
                } else {
                    el.classList.add('section-to-hide');
                    el.classList.remove('section-to-print');
                }
            });
            window.print();
            // Reset
            allSections.forEach(id => {
                const el = document.getElementById(id).closest('.col-md-6');
                el.classList.remove('section-to-print', 'section-to-hide');
            });
        }
    </script>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-info shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">SchoolSys - Student</a>
            <div class="d-flex">
                <a href="../actions/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2>Hello, <?php echo $_SESSION['user_name']; ?></h2>
                <p class="text-muted">
                    Class: <?php echo htmlspecialchars($student['class_name'] . ' ' . $student['section']); ?> | 
                    Admission No: <?php echo htmlspecialchars($student['admission_no']); ?>
                </p>
            </div>
            <div class="col-md-4 text-end">
                <?php 
                $photo_filename = $student['photo'] ?? '';
                $photo_url = "../uploads/" . $photo_filename;
                if(!empty($photo_filename) && file_exists($photo_url)): ?>
                    <img src="<?php echo $photo_url; ?>" class="rounded-circle shadow" width="120" height="120" style="object-fit:cover; border: 4px solid #fff;">
                <?php else: ?>
                    <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle shadow" style="width: 120px; height: 120px; border: 4px solid #fff;">
                        <i class="fas fa-user fa-5x text-light"></i>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <!-- Results Section -->
            <div class="col-md-6 mb-4">
                <div class="card shadow border-0 h-100" id="results-card">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-primary mb-0"><i class="fas fa-poll me-2"></i>Exam Results</h5>
                        <button onclick="printSection('results-card')" class="btn btn-outline-primary btn-sm print-hide">
                            <i class="fas fa-print me-1"></i> Print
                        </button>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Exam</th>
                                        <th>Subject</th>
                                        <th>Marks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($result_rows as $res): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($res['exam_name']); ?></td>
                                        <td><?php echo htmlspecialchars($res['subject_name']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $res['marks_obtained'] >= 40 ? 'success' : 'danger'; ?>">
                                                <?php echo $res['marks_obtained']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fees Section -->
            <div class="col-md-6 mb-4">
                <div class="card shadow border-0 h-100" id="fees-card">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-success mb-0"><i class="fas fa-money-bill-wave me-2"></i>Fees Status</h5>
                        <button onclick="printSection('fees-card')" class="btn btn-outline-success btn-sm print-hide">
                            <i class="fas fa-print me-1"></i> Print
                        </button>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($fee_rows as $fee): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($fee['month'] . ' ' . $fee['year']); ?></td>
                                        <td>$<?php echo number_format($fee['amount'], 2); ?></td>
                                        <td>
                                            <?php if($fee['status'] == 'Paid'): ?>
                                                <span class="badge bg-success">Paid</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Section -->
            <div class="col-md-6 mb-4">
                <div class="card shadow border-0 h-100" id="attendance-card">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-info mb-0"><i class="fas fa-calendar-check me-2"></i>Attendance</h5>
                        <button onclick="printSection('attendance-card')" class="btn btn-outline-info btn-sm print-hide">
                            <i class="fas fa-print me-1"></i> Print
                        </button>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(count($attendance_rows) > 0): ?>
                                        <?php foreach($attendance_rows as $att): ?>
                                        <tr>
                                            <td><?php echo date('d M Y', strtotime($att['date'])); ?></td>
                                            <td>
                                                <?php 
                                                if($att['status'] == 'Present') {
                                                    echo '<span class="badge bg-success">Present</span>';
                                                } elseif($att['status'] == 'Absent') {
                                                    echo '<span class="badge bg-danger">Absent</span>';
                                                } else {
                                                    echo '<span class="badge bg-warning text-dark">'.htmlspecialchars($att['status']).'</span>';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="2" class="text-center text-muted">No attendance records found.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// admin/fees.php
require_once 'includes/header.php';

// Fetch all fees
$sql = "SELECT f.*, s.admission_no, u.name 
        FROM fees f 
        JOIN students s ON f.student_id = s.id 
        JOIN users u ON s.user_id = u.id 
        ORDER BY f.id DESC";
$fees = $pdo->query($sql)->fetchAll();

// Fetch students for modal
$students = $pdo->query("SELECT s.id, s.admission_no, u.name FROM students s JOIN users u ON s.user_id = u.id ORDER BY u.name ASC")->fetchAll();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Fees Management</h1>
    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addFeeModal">
        <i class="fas fa-plus"></i> Add New Fee
    </button>
</div>

<!-- Fees Table -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Amount</th>
                        <th>Month/Year</th>
                        <th>Status</th>
                        <th>Paid Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($fees as $fee): ?>
                    <tr>
                        <td><?php echo $fee['id']; ?></td>
                        <td>
                            <?php echo htmlspecialchars($fee['name']); ?> <br>
                            <small class="text-muted"><?php echo $fee['admission_no']; ?></small>
                        </td>
                        <td>$<?php echo number_format($fee['amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars($fee['month'] . ' ' . $fee['year']); ?></td>
                        <td>
                            <?php if($fee['status'] == 'Paid'): ?>
                                <span class="badge bg-success">Paid</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $fee['paid_date'] ? $fee['paid_date'] : '-'; ?></td>
                        <td>
                            <?php if($fee['status'] == 'Pending'): ?>
                                <a href="../actions/fee_action.php?pay=<?php echo $fee['id']; ?>" class="btn btn-sm btn-success">
                                    <i class="fas fa-check"></i> Mark Paid
                                </a>
                            <?php endif; ?>
                            <a href="../actions/fee_action.php?delete=<?php echo $fee['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this fee record?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Fee Modal -->
<div class="modal fade" id="addFeeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="../actions/fee_action.php" method="POST">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Student Fee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Student</label>
                    <select name="student_id" class="form-select" required>
                        <option value="">Select Student</option>
                        <?php foreach($students as $s): ?>
                            <option value="<?php echo $s['id']; ?>"><?php echo $s['name'] . ' (' . $s['admission_no'] . ')'; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Amount</label>
                    <input type="number" name="amount" class="form-control" step="0.01" required>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="form-label">Month</label>
                        <select name="month" class="form-select" required>
                            <option value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Year</label>
                        <input type="number" name="year" class="form-control" value="<?php echo date('Y'); ?>" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="add_fee" class="btn btn-primary">Save Fee</button>
            </div>
        </div>
    </form>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>

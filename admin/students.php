<?php
// admin/students.php
require_once 'includes/header.php';

// Handle Add Student Logic is complex (needs user + student entries), usually handled in separate action.
// For now, we display students and a link to add or a modal form.
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Students Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="add_student.php" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Add New Student
        </a>
    </div>
</div>

<!-- Students List Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">All Students</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Adm No</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Parent Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Join students with users and classes
                    $sql = "SELECT s.*, u.name, c.class_name, c.section 
                            FROM students s 
                            JOIN users u ON s.user_id = u.id 
                            LEFT JOIN classes c ON s.class_id = c.id 
                            ORDER BY s.id DESC";
                    $stmt = $pdo->query($sql);
                    while ($row = $stmt->fetch()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['admission_no'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['class_name'] . ' ' . $row['section']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                        echo "<td>
                                <a href='edit_student.php?id=" . $row['id'] . "' class='btn btn-sm btn-info text-white'><i class='fas fa-edit'></i></a>
                                <a href='../actions/student_action.php?delete=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure? This will delete the user account too!\")'><i class='fas fa-trash'></i></a>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

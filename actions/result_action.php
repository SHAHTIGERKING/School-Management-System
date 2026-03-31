<?php
// actions/result_action.php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'teacher'])) {
    header("Location: ../public/login.php");
    exit();
}

if (isset($_POST['save_results'])) {
    $class_id = $_POST['class_id'];
    $subject_id = $_POST['subject_id'];
    $exam_name = $_POST['exam_name'];
    $assignment_id = isset($_POST['assignment']) ? $_POST['assignment'] : '';
    $marks_data = $_POST['marks']; // Array [student_id => marks]

    try {
        $pdo->beginTransaction();

        $stmtDelete = $pdo->prepare("DELETE FROM results WHERE student_id = ? AND subject_id = ? AND exam_name = ?");
        $stmtInsert = $pdo->prepare("INSERT INTO results (student_id, subject_id, exam_name, marks_obtained) VALUES (?, ?, ?, ?)");

        foreach ($marks_data as $student_id => $marks) {
            // Only insert if marks are provided
            if ($marks !== '') {
                // Delete old
                $stmtDelete->execute([$student_id, $subject_id, $exam_name]);
                // Insert new
                $stmtInsert->execute([$student_id, $subject_id, $exam_name, $marks]);
            }
        }

        $pdo->commit();
        setFlashMessage('success', 'Results saved successfully.');

    } catch (PDOException $e) {
        $pdo->rollBack();
        setFlashMessage('danger', 'Error saving results: ' . $e->getMessage());
    }

    if ($_SESSION['role'] === 'teacher') {
        header("Location: ../teacher/results.php?assignment=$assignment_id&exam_name=$exam_name");
    } else {
        header("Location: ../admin/results.php?class_id=$class_id&subject_id=$subject_id&exam_name=$exam_name");
    }
    exit();
}

if ($_SESSION['role'] === 'teacher') {
    header("Location: ../teacher/results.php");
} else {
    header("Location: ../admin/results.php");
}
exit();
?>

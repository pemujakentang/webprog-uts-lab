<?php
session_start();

require_once("database.php"); // Include your database connection code if needed

if (isset($_GET['id'])) {
    $task_id = $_GET['id'];
} else {
    $_SESSION['error_message'] = "Task ID not provided for deletion.";
    header("Location: index.php"); // Redirect to the main page or appropriate page
    exit;
}

$sql = "DELETE FROM tasks WHERE id = ?";
$stmt = $connection->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $task_id);

    if ($stmt->execute()) {
        // Task deleted successfully.
        $_SESSION['success_message'] = "Task deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Error deleting the task: " . $connection->error;
    }

    $stmt->close();
} else {
    $_SESSION['error_message'] = "Error preparing the delete query: " . $connection->error;
}

header("Location: index.php"); // Redirect back to the main page or a specific page

?>

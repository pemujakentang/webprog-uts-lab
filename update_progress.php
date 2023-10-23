<?php
session_start();

require_once("database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['activity_id']) && isset($_POST['new_progress'])) {
        $activity_id = $_POST['activity_id'];
        $new_progress = $_POST['new_progress'];

        // Print the value of new_progress
        echo "New Progress Value: " . $new_progress;

        // Update the progress in the database
        $sql = "UPDATE tasks SET progress = '$new_progress' WHERE id = $activity_id";
        $result = $connection->query($sql);

        header('Location: index.php'); // Redirect back to your main page
        exit();
    }
}
?>

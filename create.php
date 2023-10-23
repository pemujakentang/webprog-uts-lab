<?php
session_start();

if (isset($_SESSION['user'])) {
    $isLoggedIn = true;
    $user_id = $_SESSION['userinfo']['id'];
} else {
    $isLoggedIn = false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task = $_POST['task'];
    $description = $_POST['description'];
    
    // Check if the description is empty and set it to "-"
    if (empty($description)) {
        $description = "-";
    }
    
    // Set the default value for the date input to the current date
    $raw_date = empty($_POST['date']) ? date('Y-m-d') : $_POST['date'];
    $formatted_date = date('Y-m-d', strtotime($raw_date)); 
    
    $progress = $_POST['progress'];
    
    require_once("database.php");
    $sql = "INSERT INTO tasks (task, description, date, progress, user_id) VALUES (?, ?, ?, ?, ?)";

    
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sssss", $task, $description, $formatted_date, $progress, $user_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Task added successfully."; 
    } else {
        $_SESSION['error_message'] = "Task insertion failed. Please try again."; 
    }
}

header("Location: index.php");

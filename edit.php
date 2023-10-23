<!-- edit.php -->

<?php
session_start();
require_once("database.php");

if (isset($_GET['id'])) {
    $taskId = $_GET['id'];
    $sql = "SELECT * FROM tasks WHERE id = $taskId";
    $result = $connection->query($sql);

    if (!$result) {
        die("Invalid query: " . $connection->error);
    }

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
    } else {}
} else {}

if (isset($_POST['update_task'])) {
    $newTask = $_POST['new_task'];
    $newDescription = $_POST['new_description'];
    $newDate = $_POST['new_date'];
    $newProgress = $_POST['new_progress'];

    $updateSql = "UPDATE tasks SET task = '$newTask', description = '$newDescription', date = '$newDate', progress = '$newProgress' WHERE id = $taskId";
    if ($connection->query($updateSql) === TRUE) {
        $_SESSION['success_message'] = "Task updated successfully.";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Task update failed: " . $connection->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script defer src="app.js"></script>
<link rel="stylesheet" type="text/css" href="styles.css">

</head>
<body>
    <section class="hidden elements">
        <div class="col-3 p-0">
                        <div class="login-image">
                            <img class="side-image" src="https://www.timeshighereducation.com/sites/default/files/styles/the_breaking_news_image_style/public/person-writing-letter-with-metal-quill.jpg?itok=lCt7Bo6c" />
                        <div class="image-tint"></div>
                        </div>
                </div>
    </section>
        <div class="container-fluid d-flex align-items-center justify-content-center background-primary" style="height: 100vh;">            
        <section class="hidden elements">

            <div class="mx-auto rounded login-form show">
                    <h1 class="text-center">Edit Task</h1>
                    <form method="post">
                        <div class="mb-3 mx-auto login-input-field">
                            <label for="new_task" class="form-label">Task</label>
                            <div>
                                <input id='new_task' class="form-control" type="text" name="new_task" value="<?php echo $row['task']; ?>">
                            </div>
                        </div>
                        <div class="mb-3 mx-auto login-input-field">
                            <label for="new_description" class="form-label">Description</label>
                            <div>
                                <textarea class="form-control" id="new_description" name="new_description"><?php echo $row['description']; ?></textarea>
                            </div>
                        </div>
                        <div class="mb-3 mx-auto login-input-field">
                            <label for="new_date" class="form-label">Date Finished</label>
                            <div>
                                <input type="date" name="new_date" class="form-control" value="<?php echo $row['date']; ?>">
                            </div>
                        </div>
                        <div class="mb-3 mx-auto login-input-field">
                            <label class="form-label" for="new_progress">Progress</label>
                            <div>
                                <select name="new_progress" id="new_progress" class="form_control">
                                    <option value="Not Started" <?php if ($row['progress'] == 'Not Started') echo 'selected'; ?>>Not Yet Started</option>
                                    <option value="In Progress" <?php if ($row['progress'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                                    <option value="Waiting On" <?php if ($row['progress'] == 'Waiting On') echo 'selected'; ?>>Waiting On</option>
                                    <option value="Completed" <?php if ($row['progress'] == 'Completed') echo 'selected'; ?>>Completed</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 mx-auto login-input-field">
                            <button type="submit" class="btn submit-button" name="update_task">Update</button>
                            <a href="index.php" class="btn btn-warning">Cancel</a>
                        </div>
                    </form>
                </div>
        </section>    
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>

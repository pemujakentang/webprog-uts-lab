<?php
session_start();

if (isset($_SESSION['user'])) {
    $isLoggedIn = true;
    $userid = $_SESSION['userinfo']['id'];
    $sql = $sql = "SELECT * FROM tasks WHERE user_id = $userid ORDER BY
CASE
    WHEN progress = 'Not Started' THEN 1
    WHEN progress = 'In Progress' THEN 2
    WHEN progress = 'Waiting On' THEN 3
    WHEN progress = 'Completed' THEN 4
END";
} else {
    $isLoggedIn = false;
    $sql = $sql = "SELECT 1 FROM DUAL WHERE FALSE";
}

require_once("database.php");;
$result = $connection->query($sql);

if (!$result) {
    die("Invalid query: " . $connection->error);
}

if (isset($_SESSION['user'])) {
    $isLoggedIn = true;
} else {
    $isLoggedIn = false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script defer src="app.js"></script>

</head>

<body>
    <header class="navbar navbar-expand-lg bd-navbar sticky-top primary-background-color">
        <nav class="container-xxl bd-gutter flex-wrap flex-lg-nowrap navigation-bar" id="navbarhead">
            <div class="headertitle">
                <img class="icons" id="icon" src="logo.png">
                <div class="logo">
                    U To-Do List
                </div>
            </div>
            <div class="actbuttonheader">
            <?php
            if (isset($_SESSION['user'])) {
                echo '<a href="logout.php" id="logoutbadge">Logout</a>';
                // echo $_SESSION['user'];
                echo "<span class='usernamebadge' id='usernamebadge'>".$_SESSION['userinfo']['username']."</span>" ;
            }
            ?>
            </div>
        </nav>
    </header>

    <div class="container text-center">
        <?php
        if (!$isLoggedIn) {
            echo "<section class='hidden elements'>";
            echo "<p>To view/add your activities, <a href='login.php'>Please login here.</a></p>";
            echo "</section>";
        } else {
            echo "<section class='hidden elements'>";
            echo "<div id='buttoncreatediv'>";
            echo '<button id="showForm" type="button" class="addbutton">Add a Task</button>';
            echo "<div id='toDoForm' style='display:none;'>";
            echo "<form action='create.php' method='post'>";
            echo "<div class='mb-3 mx-auto login-input-field' id='createbar'>";
            echo "<label>Task</label>";
            echo "<div>";
            echo "<input class='form-control' type='text' name='task' placeholder='Enter your task' value=''>";
            echo "</div>";
            echo "</div>";
            echo "<div class='mb-3 mx-auto login-input-field' id='createbar'>";
            echo "<label>Description</label>";
            echo "<div>";
            echo "<textarea class='form-control' name='description' placeholder='task description' value=''></textarea>";
            echo "</div>";
            echo "</div>";
            echo "<div class='mb-3 mx-auto login-input-field' id='createbar'>";
            echo "<label>Date Finished</label>";
            echo "<div>";
            echo "<input class='form-control' type='date' name='date'/>";
            echo "</div>";
            echo "</div>";
            echo "<div class='mb-3 mx-auto login-input-field' id='createbar'>";
            echo "<div>";
            echo "<label>Progress</label>";
            echo "</div>";
            echo "<select name='progress' class='form-select'>";
            echo "<option value='Not Started'>Not Yet Started</option>";
            echo "<option value='In Progress'>In Progress</option>";
            echo "<option value='Waiting On'>Waiting On</option>";
            echo "<option value='Completed'>Completed</option>";
            echo "</select>";
            echo "</div class='mb-3 mx-auto login-input-field'>";
            echo "<button type='submit' class='addbtn'>Add</button>";
            echo "<button id='cancelButton' class='cancelbtn' type='button'>Cancel</button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
            echo "</section>";
        }
        ?>
        <div>
            <?php
            if (isset($_SESSION['user'])) {
                if (isset($_SESSION['success_message'])) {
                    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
                    unset($_SESSION['success_message']);
                }
                if (isset($_SESSION['error_message'])) {
                    echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
                    unset($_SESSION['error_message']);
                }
            }
            ?>
        </div>
        <div class="mx-auto">
            <table class="table">
                <thead>
                    <tr id="ths">
                        <th scope="col" class="col-3" id="ths">Task</th>
                        <th scope="col" class="col-4" id="ths">Description</th>
                        <th scope="col" class="col-2" id="ths">Date</th>
                        <th scope="col" class="col-1" id="ths">Completed</th>
                        <th scope="col" class="col-3" id="ths">Progress</th>
                        <th scope="col" class="col-3" id="ths">Action</th>
                    </tr>
                </thead>
                <tbody id="tbodyresult">
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr class=''>";
                        echo "<td class='align-items-center'>{$row['task']}</td>";
                        echo "<td>{$row['description']}</td>";
                        echo "<td>{$row['date']}</td>";
                        echo "<form method='POST' action='update_progress.php'>";
                        if ($isLoggedIn) {
                            echo "<section class='hidden elements'>";
                            echo "<td class='checkbox-container' id='checkbox-" . $row['id'] . "'>";
                            echo "<input type='checkbox' class='form-check-label' onchange='confirmChange(this)' name='new_progress' " . (($row['progress'] == 'Completed') ? 'checked' : '') . ">";
                            echo "<input type='hidden' name='activity_id' value='{$row['id']}'>";
                            echo "<input type='hidden' name='form_id' value='form-" . $row['id'] . "'>";
                            echo "</td>";
                            echo "</form>";
                            echo "<td class='progress-dropdown'>";
                            echo "<form method='POST' action='update_progress.php' id='form-" . $row['id'] . "'>";
                            echo "<select name='new_progress' class='form-select ";
                            if ($row['progress'] == 'Not Started') {
                                echo 'option-not-started';
                            } elseif ($row['progress'] == 'In Progress') {
                                echo 'option-in-progress';
                            } elseif ($row['progress'] == 'Waiting On') {
                                echo 'option-waiting-on';
                            } elseif ($row['progress'] == 'Completed') {
                                echo 'option-completed';
                            }
                            echo "' onchange='this.form.submit()'>";
                            echo "<option value='Not Started' " . ($row['progress'] == 'Not Started' ? 'selected' : '') . ">Not Yet Started</option>";
                            echo "<option value='In Progress' " . ($row['progress'] == 'In Progress' ? 'selected' : '') . ">In Progress</option>";
                            echo "<option value='Waiting On' " . ($row['progress'] == 'Waiting On' ? 'selected' : '') . ">Waiting On</option>";
                            echo "<option value='Completed' " . ($row['progress'] == 'Completed' ? 'selected' : '') . ">Completed</option>";
                            echo "</select>";

                            echo "<input type='hidden' name='activity_id' value='{$row['id']}'>";
                            echo "<input type='hidden' name='form_id' value='form-" . $row['id'] . "'>";
                            echo "</form>";
                            echo "</td>";
                            echo "<td class='d-flex no-border h-100 gap-2'>";
                            echo "<a class='' href='edit.php?id={$row['id']}'><i class='fa-solid fa-square-pen w-100'></i></a> ";
                            echo "<a href='#' onClick='confirmDelete({$row['id']})'><i class='fa-solid fa-circle-xmark w-100' style='color: #ff0000;'></i></a>";
                            echo "</td>";
                            echo "</section>";
                        } else {
                            echo "<td class='checkbox-container' id='checkbox-" . $row['id'] . "'>";
                            echo "<input type='checkbox' disabled>";
                            echo "<input type='hidden' name='activity_id' value='{$row['id']}'>";
                            echo "<input type='hidden' name='form_id' value='form-" . $row['id'] . "'>";
                            echo "</td>";
                            echo "<td class='progress-dropdown'>";
                            echo "<form method='POST' action='update_progress.php' id='form-" . $row['id'] . "'>";
                            echo "<select name='new_progress' class='form-select ";
                            if ($row['progress'] == 'Not Started') {
                                echo 'option-not-started';
                            } elseif ($row['progress'] == 'In Progress') {
                                echo 'option-in-progress';
                            } elseif ($row['progress'] == 'Waiting On') {
                                echo 'option-waiting-on';
                            } elseif ($row['progress'] == 'Completed') {
                                echo 'option-completed';
                            }
                            echo "' onchange='this.form.submit()' disabled>";
                            echo "<option value='Not Started' " . ($row['progress'] == 'Not Started' ? 'selected' : '') . ">Not Yet Started</option>";
                            echo "<option value='In Progress' " . ($row['progress'] == 'In Progress' ? 'selected' : '') . ">In Progress</option>";
                            echo "<option value='Waiting On' " . ($row['progress'] == 'Waiting On' ? 'selected' : '') . ">Waiting On</option>";
                            echo "<option value='Completed' " . ($row['progress'] == 'Completed' ? 'selected' : '') . ">Completed</option>";
                            echo "</select>";
                            echo "<input type='hidden' name='activity_id' value='{$row['id']}'>";
                            echo "<input type='hidden' name='form_id' value='form-" . $row['id'] . "'>";
                            echo "</form>";
                        }
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

        </div>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <script>
            document.getElementById('showForm').addEventListener('click', function() {
                var form = document.getElementById('toDoForm');
                if (form.style.display === 'none' || form.style.display === '') {
                    form.style.display = 'block';
                } else {
                    form.style.display = 'none';
                    document.getElementById('task').value = '';
                    document.getElementById('description').value = '';
                    document.getElementById('date').value = '';
                }
            });

            document.getElementById('cancelButton').addEventListener('click', function() {
                var form = document.getElementById('toDoForm');
                form.style.display = 'none';

                document.getElementById('task').value = '';
                document.getElementById('description').value = '';
                document.getElementById('date').value = '';
            });

            function confirmDelete(taskId) {
                if (confirm("Are you sure you want to delete this task?")) {
                    window.location.href = 'delete.php?id=' + taskId;
                } else {}
            }
        </script>
        <script>
            function confirmChange(checkbox) {
                var formId = checkbox.form.elements['form_id'].value;
                var form = document.getElementById(formId);

                if (checkbox.checked) {
                    if (confirm("Are you sure you want to mark this task as completed?")) {
                        form.elements['new_progress'].value = "Completed";
                    } else {
                        checkbox.checked = false;
                        form.elements['new_progress'].value = "Not Started";
                    }
                } else {
                    form.elements['new_progress'].value = "Not Started";
                }

                form.submit();
            }
        </script>
    </div>
</body>

</html>
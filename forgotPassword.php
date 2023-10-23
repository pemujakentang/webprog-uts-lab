<?php
session_start();

if (isset($_POST["forgotPassword"])) {
    $username_or_email = $_POST["username_or_email"];
    $_SESSION["username_or_email"] = $username_or_email; // Store in session

    require_once("database.php");

    $field = filter_var($username_or_email, FILTER_VALIDATE_EMAIL) ? "email" : "username";

    $errors = array();

    if (empty($username_or_email)) {
        array_push($errors, "Please fill in the input field.");
    } else {
        $sql = "SELECT * FROM users WHERE $field = ?";
        $stmt = mysqli_prepare($connection, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username_or_email);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

            if ($user) {
                header("Location: forgotPasswordConfirmation.php");
                die();
            } else {
                array_push($errors, "Username or Email cannot be found");
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script defer src="app.js"></script>
</head>
<body>
<div class="col-3 p-0">
    <div class="login-image">
        <img class="side-image" src="https://www.timeshighereducation.com/sites/default/files/styles/the_breaking_news_image_style/public/person-writing-letter-with-metal-quill.jpg?itok=lCt7Bo6c" />
    <div class="image-tint"></div>
    </div>
</div>
    <div class="container-fluid d-flex align-items-center justify-content-center background-primary" style="height: 100vh;">
    <section class="hidden">    
    <div class="mx-auto rounded login-form">
            <div>
                <h1 class="text-center">
                    Forgotten password
                </h1>
                <form action="forgotPassword.php" method="post">
                    <div class="mb-3 mx-auto login-input-field">
                    <?php
                    if (isset($_POST["forgotPassword"])) {
                    if (count($errors) > 0) {
                        foreach ($errors as $error) {
                            echo "<div class='alert alert-danger'>$error</div>";
                        }
                    }
                }
                ?>
                        <label for="username_or_email" class="form-label">Please enter your username or email</label>
                        <input type="text" id="username_or_email" class="form-control" placeholder="Username or Email" name="username_or_email">
                    </div>
                    <div class="mb-3 mx-auto login-input-field">
                        <button class="btn submit-button" type="submit" value="forgotPassword" name="forgotPassword">Verify</button>
                    </div>
                </form>
            </div>
            </section>
        </div>

</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>

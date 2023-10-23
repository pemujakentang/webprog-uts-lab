<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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
                    Forgotten Password
                </h1>
            </div>
            <?php
            session_start();
            
            $errors = array();
            
            if (isset($_POST["passwordConfirmation"])) {
                $newPassword = $_POST["new_password"];
                $passwordConfirmation = $_POST["password_confirmation"];
            
                
                if (isset($_SESSION["username_or_email"])) {
                    $username_or_email = $_SESSION["username_or_email"];
                    require_once("database.php");
                    $field = filter_var($username_or_email, FILTER_VALIDATE_EMAIL) ? "email" : "username";
                    $sql = "SELECT password FROM users WHERE $field = ?";
                    $stmt = mysqli_prepare($connection, $sql);
                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "s", $username_or_email);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        if ($user) {
                            $currentPasswordHash = $user["password"];
            
                            if (password_verify($newPassword, $currentPasswordHash)) {
                                array_push($errors, "New password cannot be the same as the current password.");
                            } elseif (empty($newPassword) || empty($passwordConfirmation)) {
                                array_push($errors, "Password fields cannot be empty.");
                            } elseif (strlen($newPassword) < 8) {
                                array_push($errors, "New password must be at least 8 characters long.");
                            } elseif ($newPassword !== $passwordConfirmation) {
                                array_push($errors, "Passwords do not match. Please try again.");
                            } else {
                                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                                $sql = "UPDATE users SET password = ? WHERE $field = ?";
                                $stmt = mysqli_prepare($connection, $sql);
                                if ($stmt) {
                                    mysqli_stmt_bind_param($stmt, "ss", $hashedPassword, $username_or_email);
                                    if (mysqli_stmt_execute($stmt)) {
                                        echo "<div>Password updated successfully!</div>";
                                        header("Location: login.php");
                                        session_destroy(); 
                                        die();
                                    } else {
                                        echo "<div>Something went wrong while updating the password.</div>";
                                    }
                                } else {
                                    echo "<div>Something went wrong with the database query.</div>";
                                }
                            }
                        }
                    } else {
                        echo "<div>Something went wrong with the database query.</div>";
                    }
                } else {
                    echo "<div>Session data not found. Please go through the verification process again.</div>";
                }
            }
            ?>
            <form action="forgotPasswordConfirmation.php" method="post">
            <?php
                        if (isset($_POST["passwordConfirmation"])) {

                        if (count($errors) > 0) {
                            foreach ($errors as $error) {
                                echo "<div class='alert alert-danger'>$error</div>";
                            }
                        }
                    }
            ?>    
            <div class="mb-3 mx-auto login-input-field">
                    <label class="form-label" for="new_password">Please enter your new password:</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter a new password">
                </div>
                <div class="mb-3 mx-auto login-input-field">
                    <label for="password_confirmation" class="form-label">Please reenter your new password:</label>
                    <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="Re-enter your password">
                </div>
                <div class="mb-3 mx-auto login-input-field">
                    <button class="btn submit-button" type="submit" value="passwordConfirmation" name="passwordConfirmation">Submit</button>
                </div>
            </form>
        </div>
    </section>
    </div>
<script src="https:
</body>
</html>

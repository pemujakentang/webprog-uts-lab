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
    <section class="hidden elements">
    </section>

        <div class="container-fluid d-flex align-items-center justify-content-center background-primary" style="height: 100vh;" id="log-form">
        <section class="hidden elements">    
        <div class="mx-auto rounded login-form" id="log-formup">
                <div class="login-page">
                    <h1 class="text-center">
                        Login Page
                    </h1>
                <?php
                if (isset($_POST["login"])) {
                    $username_or_email = $_POST["username_or_email"];
                    $password = $_POST["password"];
                
                    require_once("database.php");
                
                    
                    $field = filter_var($username_or_email, FILTER_VALIDATE_EMAIL) ? "email" : "username";
                
                    $sql = "SELECT * FROM users WHERE $field = ?";
                    $stmt = mysqli_prepare($connection, $sql);
                
                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "s", $username_or_email);
                        mysqli_stmt_execute($stmt);
                
                        $result = mysqli_stmt_get_result($stmt);
                        $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                        if ($user) {
                            if (password_verify($password, $user["password"])) {
                                session_start();
                                $_SESSION["user"] = "LoggedIn";
                                $_SESSION['userinfo'] = $user;
                                header("Location: index.php");
                                die();
                            } else {
                                echo "<div class='alert alert-danger'>Password does not match</div>";
                            }
                        } else {
                            echo "<div class='alert alert-danger'>Username or email does not exist</div>";
                        }
                    } else {
                        echo "<div>Something went wrong with the database query</div>";
                    }
                }
            ?>      <form action="login.php" method="post">
                        <div class="mb-3 mx-auto login-input-field" id="field">
                            <label for="username_or_email" class="form-label">Username or Email</label>
                            <input type="text" class="form-control" id="username_or_email" name="username_or_email" placeholder="Enter your username or email" required>
                        </div>
                        <div class="mb-3 mx-auto login-input-field" id="field">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                        <div class="mb-3 mx-auto login-input-field" id="field">
                            <button type="submit" class="btn submit-button" name="login">Login</button>
                        </div>
                    </form>
                    <div class="mb-3 mx-auto login-input-field" id="field">
                        <p>Forgot your password? <a href="forgotPassword.php">Click here.</a></p>
                        <p>Don't have an account? <a href="registration.php">Sign up here.</a></p>
                    </div>
                </div>
            </div>
        </section>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script defer src="app.js"></script>
</head>
<body>
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
                            Registration Page
                        </h1>
                        <?php
                        if (isset($_POST["submit"])) {
                            $username = $_POST["username"];
                            $email = $_POST["email"];
                            $password = $_POST["password"];
                            $password_confirmation = $_POST["password_confirmation"];
                        
                            $password_hash = password_hash($password, PASSWORD_BCRYPT);
                        
                            $username_errors = array();
                            $email_errors = array();
                        
                            if (empty($username) or empty($email) or empty($password) or empty($password_confirmation)) {
                                array_push($username_errors, "All fields must be filled");
                            }
                            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                array_push($email_errors, "Email is not valid");
                            }
                            if (strlen($password) < 8) {
                                array_push($username_errors, "Password must be at least 8 characters long");
                            }
                            if ($password !== $password_confirmation) {
                                array_push($username_errors, "Password doesn't match");
                            }
                        
                            require_once("database.php");
                        
                            
                            $sql = "SELECT * FROM users WHERE email = '$email'";
                            $result = mysqli_query($connection, $sql);
                            $rowCount = mysqli_num_rows($result);
                            if ($rowCount > 0) {
                                array_push($email_errors, "Email already exists");
                            } 
                        
                            
                            $sqluser = "SELECT * FROM users WHERE username = '$username'";
                            $username_result = mysqli_query($connection, $sqluser);
                            $username_rowCount = mysqli_num_rows($username_result);
                            if ($username_rowCount > 0) {
                                array_push($username_errors, "Username already exists");
                            }
                        
                            if (count($username_errors) > 0) {
                                foreach ($username_errors as $error) {
                                    echo "<div class='error alert alert-danger'>$error</div>";
                                }
                            }
                        
                            if (count($email_errors) > 0) {
                                foreach ($email_errors as $error) {
                                    echo "<div class='error alert alert-danger'>$error</div>";
                                }
                            }
                        
                            if (empty($username_errors) && empty($email_errors)) {
                                require_once("database.php");
                                $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                                $stmt = mysqli_stmt_init($connection);
                                $prepStmt = mysqli_stmt_prepare($stmt, $sql);
                                if ($prepStmt) {
                                    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password_hash);
                                    mysqli_stmt_execute($stmt);
                                    header("Location: login.php");
                                } else {
                                    echo "<div class='error '>Something went wrong!</div>";
                                }
                            }
                        }
                        ?>
                            <form action="registration.php" method="post">
                                <div class="mb-3 mx-auto login-input-field">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" id="username" class="form-control" name="username" placeholder="Username" />
                                </div>
                                <div class="mb-3 mx-auto login-input-field">
                                <label for="email" class="form-label">Email</label>
                                    <input type="text" id="email" class="form-control" name="email" placeholder="Email" />
                                </div>
                                <div class="mb-3 mx-auto login-input-field">
                                <label for="password" class="form-label">Set Your Password</label>
                                    <input type="password" id="password" class="form-control" name="password" placeholder="Password" />
                                </div>
                                <div class="mb-3 mx-auto login-input-field">
                                <label for="password_confirmation" class="form-label">Re-enter Your Password</label>
                                    <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="Password Confirmation" />
                                </div>
                                <div class="mb-3 mx-auto login-input-field">
                                    <input type="submit" class="btn submit-button" value="Register" name="submit" />
                                </div>
                            </form>
                    </div>
                </div>
        </section>    
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
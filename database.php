<!-- ganti sesuai dengan database yang dibuat -->
<?php
$host = "localhost:3306";
$user = "root";
$pass = "";
$dbName = "webprog-lab";

$connection = mysqli_connect($host, $user, $pass, $dbName);
if(!$connection){
    die("Something went wrong");
}
?>
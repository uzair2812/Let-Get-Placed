<?php
session_start();
include("db_connect.php");

if(!isset($_SESSION['student_id']))
{
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$message = "";

if(isset($_POST['change']))
{
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    $check = mysqli_query($conn,
    "SELECT * FROM students
    WHERE student_id='$student_id'
    AND password='$old_password'");

    if(mysqli_num_rows($check)>0)
    {
        mysqli_query($conn,
        "UPDATE students
        SET password='$new_password'
        WHERE student_id='$student_id'");

        $message = "Password Changed Successfully";
    }
    else
    {
        $message = "Old Password Incorrect";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Change Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-5">

<div class="card">

<div class="card-body">

<h2>Change Password</h2>

<?php
if($message!="")
{
echo "<div class='alert alert-info'>$message</div>";
}
?>

<form method="POST">

<input
type="password"
name="old_password"
class="form-control mb-3"
placeholder="Old Password"
required>

<input
type="password"
name="new_password"
class="form-control mb-3"
placeholder="New Password"
required>

<button
name="change"
class="btn btn-success">

Change Password

</button>

</form>

</div>

</div>

</div>

</div>

</div>

</body>
</html>
<?php
include("db_connect.php");

$message = "";

if(isset($_POST['reset']))
{
    $usn = $_POST['usn'];

    $check = mysqli_query($conn,
    "SELECT * FROM students
    WHERE usn='$usn'");

    if(mysqli_num_rows($check)>0)
    {
        $message =
        "Contact Placement Officer to reset your password.";
    }
    else
    {
        $message =
        "Student Not Found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Forgot Password</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-5">

<div class="card">

<div class="card-body">

<h2 class="text-center">
Forgot Password
</h2>

<?php
if($message!="")
{
echo "<div class='alert alert-info'>$message</div>";
}
?>

<form method="POST">

<input
type="text"
name="usn"
class="form-control mb-3"
placeholder="Enter USN"
required>

<button
name="reset"
class="btn btn-primary w-100">

Submit

</button>

</form>

<br>

<a href="student_login.php">
Back To Login
</a>

</div>

</div>

</div>

</div>

</div>

</body>
</html>
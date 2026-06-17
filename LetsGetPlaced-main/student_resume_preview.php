<?php
session_start();
include("db_connect.php");

if(!isset($_SESSION['admin_id']))
{
    header("Location: login.php");
    exit();
}

$id = $_GET['student_id'];

$resume = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT * FROM resumes
WHERE student_id='$id'")
);
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Resume</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card">

<div class="card-body">

<h2>Student Resume</h2>

<?php
if(!empty($resume['resume_file']))
{
?>

<a
href="uploads/resumes/<?php echo $resume['resume_file']; ?>"
target="_blank"
class="btn btn-primary">

Download Resume

</a>

<?php
}
else
{
echo "<div class='alert alert-warning'>Resume Not Uploaded</div>";
}
?>

</div>

</div>

</div>

</body>
</html>
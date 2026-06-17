<?php
session_start();
include("db_connect.php");

if(!isset($_SESSION['student_id']))
{
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

if(isset($_POST['add']))
{
    $company_name = $_POST['company_name'];
    $role = $_POST['role'];
    $duration = $_POST['duration'];
    $description = $_POST['description'];

    mysqli_query($conn,
    "INSERT INTO internships
    (
    student_id,
    company_name,
    role,
    duration,
    description
    )
    VALUES
    (
    '$student_id',
    '$company_name',
    '$role',
    '$duration',
    '$description'
    )");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Internships</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

<h2>Internships</h2>

<div class="card">
<div class="card-body">

<form method="POST">

<input
type="text"
name="company_name"
class="form-control mb-3"
placeholder="Company Name">

<input
type="text"
name="role"
class="form-control mb-3"
placeholder="Role">

<input
type="text"
name="duration"
class="form-control mb-3"
placeholder="Duration">

<textarea
name="description"
class="form-control mb-3"
placeholder="Internship Description"></textarea>

<button
name="add"
class="btn btn-success">

Add Internship

</button>

</form>

</div>
</div>

<br>

<table class="table table-bordered">

<tr>
<th>Company</th>
<th>Role</th>
<th>Duration</th>
<th>Description</th>
</tr>

<?php

$result = mysqli_query($conn,
"SELECT * FROM internships
WHERE student_id='$student_id'");

while($row=mysqli_fetch_assoc($result))
{
?>

<tr>

<td><?php echo $row['company_name']; ?></td>

<td><?php echo $row['role']; ?></td>

<td><?php echo $row['duration']; ?></td>

<td><?php echo $row['description']; ?></td>

</tr>

<?php
}
?>

</table>

</div>

</body>
</html>
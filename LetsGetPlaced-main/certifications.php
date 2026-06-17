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
    $certification_name = $_POST['certification_name'];
    $issued_by = $_POST['issued_by'];
    $completion_date = $_POST['completion_date'];

    mysqli_query($conn,
    "INSERT INTO certifications
    (student_id,certification_name,issued_by,completion_date)
    VALUES
    ('$student_id','$certification_name','$issued_by','$completion_date')");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Certifications</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

<h2>My Certifications</h2>

<div class="card">
<div class="card-body">

<form method="POST">

<input
type="text"
name="certification_name"
class="form-control mb-3"
placeholder="Certification Name"
required>

<input
type="text"
name="issued_by"
class="form-control mb-3"
placeholder="Issued By"
required>

<input
type="date"
name="completion_date"
class="form-control mb-3">

<button
name="add"
class="btn btn-primary">

Add Certification

</button>

</form>

</div>
</div>

<br>

<table class="table table-bordered">

<tr>
<th>Certification</th>
<th>Issued By</th>
<th>Date</th>
</tr>

<?php

$result = mysqli_query($conn,
"SELECT * FROM certifications
WHERE student_id='$student_id'");

while($row=mysqli_fetch_assoc($result))
{
?>

<tr>

<td><?php echo $row['certification_name']; ?></td>

<td><?php echo $row['issued_by']; ?></td>

<td><?php echo $row['completion_date']; ?></td>

</tr>

<?php
}
?>

</table>

</div>

</body>
</html>
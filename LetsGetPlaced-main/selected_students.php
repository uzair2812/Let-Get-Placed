<?php
session_start();
include("db_connect.php");

if(!isset($_SESSION['admin_id']))
{
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Selected Students</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

<h2>Selected Students</h2>

<table class="table table-bordered">

<tr>
<th>Student</th>
<th>Company</th>
<th>Status</th>
</tr>

<?php

$sql = "
SELECT
students.full_name,
companies.company_name,
registrations.status

FROM registrations

INNER JOIN students
ON registrations.student_id=students.student_id

INNER JOIN companies
ON registrations.company_id=companies.company_id

WHERE registrations.status='Selected'
";

$result = mysqli_query($conn,$sql);

while($row=mysqli_fetch_assoc($result))
{
?>

<tr>

<td><?php echo $row['full_name']; ?></td>

<td><?php echo $row['company_name']; ?></td>

<td><?php echo $row['status']; ?></td>

</tr>

<?php
}
?>

</table>

</div>

</body>
</html>
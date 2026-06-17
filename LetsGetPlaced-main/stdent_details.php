<?php

session_start();
include("db_connect.php");

$id = $_GET['id'];

$student = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT * FROM students
WHERE student_id='$id'")
);

?>

<!DOCTYPE html>
<html>
<head>

<title>Student Details</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card">

<div class="card-body">

<h2>
Student Profile
</h2>

<table class="table table-bordered">

<tr>
<th>Name</th>
<td>
<?php echo $student['full_name']; ?>
</td>
</tr>

<tr>
<th>USN</th>
<td>
<?php echo $student['usn']; ?>
</td>
</tr>

<tr>
<th>Email</th>
<td>
<?php echo $student['email']; ?>
</td>
</tr>

<tr>
<th>Phone</th>
<td>
<?php echo $student['phone']; ?>
</td>
</tr>

<tr>
<th>Branch</th>
<td>
<?php echo $student['branch']; ?>
</td>
</tr>

<tr>
<th>Semester</th>
<td>
<?php echo $student['semester']; ?>
</td>
</tr>

<tr>
<th>Address</th>
<td>
<?php echo $student['address']; ?>
</td>
</tr>

</table>

<a
href="manage_students.php"
class="btn btn-primary">

Back

</a>

</div>

</div>

</div>

</body>
</html>
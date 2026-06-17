<?php
session_start();
include("db_connect.php");

if(!isset($_SESSION['admin_id']))
{
    header("Location: login.php");
    exit();
}

$company_id = $_GET['company_id'];

$company = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT * FROM companies
WHERE company_id='$company_id'")
);

$cgpa = $company['eligibility_cgpa'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Eligible Students</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-4">

<h2>
Eligible Students -
<?php echo $company['company_name']; ?>
</h2>

<table class="table table-bordered">

<tr>
<th>Name</th>
<th>USN</th>
<th>CGPA</th>
<th>Branch</th>
</tr>

<?php

$result = mysqli_query($conn,
"SELECT * FROM students
WHERE cgpa >= '$cgpa'");

while($row=mysqli_fetch_assoc($result))
{
?>

<tr>

<td><?php echo $row['full_name']; ?></td>

<td><?php echo $row['usn']; ?></td>

<td><?php echo $row['cgpa']; ?></td>

<td><?php echo $row['branch']; ?></td>

</tr>

<?php
}
?>

</table>

</div>

</body>
</html>
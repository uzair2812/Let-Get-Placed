<?php

session_start();
include("db_connect.php");

$student_id = $_SESSION['student_id'];

if(isset($_POST['add']))
{
    $title = $_POST['title'];
    $description = $_POST['description'];
    $technology = $_POST['technology'];

    mysqli_query($conn,
    "INSERT INTO student_projects
    (
    student_id,
    project_title,
    project_description,
    technologies_used
    )
    VALUES
    (
    '$student_id',
    '$title',
    '$description',
    '$technology'
    )");
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Projects</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-4">

<h2>Projects</h2>

<div class="card">

<div class="card-body">

<form method="POST">

<input
type="text"
name="title"
class="form-control mb-3"
placeholder="Project Title">

<textarea
name="description"
class="form-control mb-3"
placeholder="Project Description"></textarea>

<input
type="text"
name="technology"
class="form-control mb-3"
placeholder="Technologies Used">

<button
name="add"
class="btn btn-primary">

Add Project

</button>

</form>

</div>

</div>

<br>

<table class="table table-bordered">

<tr>

<th>Project</th>
<th>Description</th>
<th>Technology</th>

</tr>

<?php

$result = mysqli_query($conn,
"SELECT * FROM student_projects
WHERE student_id='$student_id'");

while($row=mysqli_fetch_assoc($result))
{
?>

<tr>

<td>
<?php echo $row['project_title']; ?>
</td>

<td>
<?php echo $row['project_description']; ?>
</td>

<td>
<?php echo $row['technologies_used']; ?>
</td>

</tr>

<?php
}
?>

</table>

</div>

</body>
</html>
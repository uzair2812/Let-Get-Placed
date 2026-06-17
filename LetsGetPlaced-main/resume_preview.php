<?php

session_start();
include("db_connect.php");

if(!isset($_SESSION['student_id']))
{
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

$student = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT * FROM students
WHERE student_id='$student_id'")
);
?>

<!DOCTYPE html>
<html>
<head>

<title>Resume Preview</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

.resume{
background:white;
padding:40px;
box-shadow:0px 2px 10px rgba(0,0,0,0.1);
}

</style>

</head>

<body class="bg-light">

<div class="container mt-4">

<div class="resume">

<h1>
<?php echo $student['full_name']; ?>
</h1>

<p>
Email:
<?php echo $student['email']; ?>
</p>

<p>
Phone:
<?php echo $student['phone']; ?>
</p>

<hr>

<h3>Career Objective</h3>

<p>
<?php echo $student['career_objective']; ?>
</p>

<hr>

<h3>Skills</h3>

<ul>

<?php

$skills = mysqli_query($conn,
"SELECT * FROM student_skills
WHERE student_id='$student_id'");

while($skill=mysqli_fetch_assoc($skills))
{
?>

<li>
<?php echo $skill['skill_name']; ?>
</li>

<?php
}
?>

</ul>

<hr>

<h3>Projects</h3>

<?php

$projects = mysqli_query($conn,
"SELECT * FROM student_projects
WHERE student_id='$student_id'");

while($project=mysqli_fetch_assoc($projects))
{
?>

<h5>
<?php echo $project['project_title']; ?>
</h5>

<p>
<?php echo $project['project_description']; ?>
</p>

<?php
}
?>

</div>

</div>

</body>
</html>
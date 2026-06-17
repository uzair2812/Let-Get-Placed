<?php

session_start();
include("db_connect.php");

$student_id = $_SESSION['student_id'];

if(isset($_POST['add']))
{
    $skill = $_POST['skill'];

    mysqli_query($conn,
    "INSERT INTO student_skills
    (student_id,skill_name)
    VALUES
    ('$student_id','$skill')");
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Skills</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-4">

<h2>My Skills</h2>

<form method="POST">

<div class="input-group mb-3">

<input
type="text"
name="skill"
class="form-control"
placeholder="Enter Skill">

<button
name="add"
class="btn btn-success">

Add Skill

</button>

</div>

</form>

<table class="table table-bordered">

<tr>
<th>Skill</th>
</tr>

<?php

$result = mysqli_query($conn,
"SELECT * FROM student_skills
WHERE student_id='$student_id'");

while($row=mysqli_fetch_assoc($result))
{
?>

<tr>

<td>
<?php echo $row['skill_name']; ?>
</td>

</tr>

<?php
}
?>

</table>

</div>

</body>
</html>
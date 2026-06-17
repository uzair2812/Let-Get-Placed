<?php
session_start();
include("db_connect.php");

if(!isset($_SESSION['student_id']))
{
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

if(isset($_POST['upload']))
{
    $file = $_FILES['resume']['name'];
    $tmp = $_FILES['resume']['tmp_name'];

    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if($extension == "pdf")
    {
        move_uploaded_file(
            $tmp,
            "uploads/resumes/".$file
        );

        $check = mysqli_query($conn,
        "SELECT * FROM resumes
        WHERE student_id='$student_id'");

        if(mysqli_num_rows($check)>0)
        {
            mysqli_query($conn,
            "UPDATE resumes
            SET resume_file='$file'
            WHERE student_id='$student_id'");
        }
        else
        {
            mysqli_query($conn,
            "INSERT INTO resumes(student_id,resume_file)
            VALUES('$student_id','$file')");
        }

        $msg = "Resume Uploaded Successfully";
    }
    else
    {
        $msg = "Only PDF Allowed";
    }
}

$resume = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT * FROM resumes
WHERE student_id='$student_id'")
);
?>

<!DOCTYPE html>
<html>
<head>

<title>Resume Management</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card">
<div class="card-body">

<h2>Resume Management</h2>

<?php
if(isset($msg))
{
echo "<div class='alert alert-info'>$msg</div>";
}
?>

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">

<label>Select Resume PDF</label>

<input
type="file"
name="resume"
class="form-control"
required>

</div>

<button
class="btn btn-success"
name="upload">

Upload Resume

</button>

</form>

<hr>

<?php
if(!empty($resume['resume_file']))
{
?>

<h5>Current Resume</h5>

<a
href="uploads/resumes/<?php echo $resume['resume_file']; ?>"
target="_blank"
class="btn btn-primary">

Download Resume

</a>

<?php
}
?>

</div>
</div>

</div>

</body>
</html>
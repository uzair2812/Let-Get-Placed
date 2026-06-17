<?php

session_start();
include("db_connect.php");

if(!isset($_SESSION['student_id']))
{
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$company_id = $_GET['company_id'];

$student = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT * FROM students
WHERE student_id='$student_id'")
);

$company = mysqli_fetch_assoc(
mysqli_query($conn,
"SELECT * FROM companies
WHERE company_id='$company_id'")
);

$message = "";

if($student['cgpa'] >= $company['eligibility_cgpa'])
{
    $check = mysqli_query($conn,
    "SELECT * FROM registrations
    WHERE student_id='$student_id'
    AND company_id='$company_id'");

    if(mysqli_num_rows($check)==0)
    {
        mysqli_query($conn,
        "INSERT INTO registrations
        (student_id,company_id,status)
        VALUES
        ('$student_id','$company_id','Registered')");

        $message = "Successfully Registered";
    }
    else
    {
        $message = "Already Registered";
    }
}
else
{
    $message = "Not Eligible For This Drive";
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Drive Registration</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="alert alert-info">

<h3><?php echo $message; ?></h3>

</div>

<a
href="companies.php"
class="btn btn-primary">

Back To Companies

</a>

</div>

</body>
</html>
<?php
session_start();
include("db_connect.php");

if(!isset($_SESSION['admin_id']))
{
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$result = mysqli_query($conn,
"SELECT * FROM companies WHERE company_id='$id'");

$company = mysqli_fetch_assoc($result);

if(isset($_POST['update']))
{
    $company_name = $_POST['company_name'];
    $role = $_POST['role'];
    $ctc = $_POST['ctc'];
    $cgpa = $_POST['cgpa'];
    $drive_date = $_POST['drive_date'];

    mysqli_query($conn,
    "UPDATE companies SET
    company_name='$company_name',
    role_offered='$role',
    ctc='$ctc',
    eligibility_cgpa='$cgpa',
    drive_date='$drive_date'
    WHERE company_id='$id'");

    header("Location: manage_companies.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Company</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card">
<div class="card-body">

<h2>Edit Company</h2>

<form method="POST">

<input type="text"
name="company_name"
class="form-control mb-3"
value="<?php echo $company['company_name']; ?>">

<input type="text"
name="role"
class="form-control mb-3"
value="<?php echo $company['role_offered']; ?>">

<input type="number"
step="0.01"
name="ctc"
class="form-control mb-3"
value="<?php echo $company['ctc']; ?>">

<input type="number"
step="0.01"
name="cgpa"
class="form-control mb-3"
value="<?php echo $company['eligibility_cgpa']; ?>">

<input type="date"
name="drive_date"
class="form-control mb-3"
value="<?php echo $company['drive_date']; ?>">

<button
name="update"
class="btn btn-success">
Update Company
</button>

</form>

</div>
</div>

</div>

</body>
</html>
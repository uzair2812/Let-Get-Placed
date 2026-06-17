<?php
session_start();
include("db_connect.php");

$id = $_GET['id'];

$result = mysqli_query($conn,
"SELECT * FROM companies
WHERE company_id='$id'");

$company = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>

<title>Company Details</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card">

<div class="card-body">

<h2>
<?php echo $company['company_name']; ?>
</h2>

<table class="table table-bordered">

<tr>
<th>Role Offered</th>
<td><?php echo $company['role_offered']; ?></td>
</tr>

<tr>
<th>CTC</th>
<td>₹<?php echo $company['ctc']; ?> LPA</td>
</tr>

<tr>
<th>Drive Date</th>
<td><?php echo $company['drive_date']; ?></td>
</tr>

<tr>
<th>Eligibility CGPA</th>
<td><?php echo $company['eligibility_cgpa']; ?></td>
</tr>

<tr>
<th>Description</th>
<td><?php echo $company['company_description']; ?></td>
</tr>

<tr>
<th>Selection Process</th>
<td><?php echo $company['selection_process']; ?></td>
</tr>

</table>

<?php
if(!empty($company['jd_file']))
{
?>

<a
href="uploads/job_descriptions/<?php echo $company['jd_file']; ?>"
class="btn btn-primary"
target="_blank">

Download Job Description

</a>

<?php
}
?>

<a
href="companies.php"
class="btn btn-secondary">

Back

</a>

</div>

</div>

</div>

</body>
</html>
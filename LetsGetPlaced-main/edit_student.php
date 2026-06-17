<?php

session_start();
include("db_connect.php");

$id = $_GET['id'];

$result = mysqli_query($conn,
"SELECT * FROM students WHERE student_id='$id'");

$row = mysqli_fetch_assoc($result);

if(isset($_POST['update']))
{
    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $branch = $_POST['branch'];

    mysqli_query($conn,
    "UPDATE students SET
    full_name='$name',
    email='$email',
    phone='$phone',
    branch='$branch'
    WHERE student_id='$id'");

    header("Location: manage_students.php");
}

?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Student</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card">

<div class="card-body">

<h2>Edit Student</h2>

<form method="POST">

<input
type="text"
name="full_name"
value="<?php echo $row['full_name']; ?>"
class="form-control mb-3">

<input
type="email"
name="email"
value="<?php echo $row['email']; ?>"
class="form-control mb-3">

<input
type="text"
name="phone"
value="<?php echo $row['phone']; ?>"
class="form-control mb-3">

<input
type="text"
name="branch"
value="<?php echo $row['branch']; ?>"
class="form-control mb-3">

<button
class="btn btn-success"
name="update">

Update Student

</button>

</form>

</div>

</div>

</div>

</body>
</html>
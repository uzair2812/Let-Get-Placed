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
"SELECT * FROM notifications
WHERE notification_id='$id'");

$data = mysqli_fetch_assoc($result);

if(isset($_POST['update']))
{
    $title = $_POST['title'];
    $message = $_POST['message'];

    mysqli_query($conn,
    "UPDATE notifications
    SET
    title='$title',
    message='$message'
    WHERE notification_id='$id'");

    header("Location: manage_notifications.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Notification</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

<div class="card">

<div class="card-body">

<h2>Edit Notification</h2>

<form method="POST">

<input
type="text"
name="title"
value="<?php echo $data['title']; ?>"
class="form-control mb-3">

<textarea
name="message"
class="form-control mb-3"><?php echo $data['message']; ?></textarea>

<button
name="update"
class="btn btn-success">

Update Notification

</button>

</form>

</div>

</div>

</div>

</body>
</html>
<?php
session_start();
include("db_connect.php");

if(!isset($_SESSION['admin_id']))
{
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

mysqli_query($conn,
"DELETE FROM students
WHERE student_id='$id'");

header("Location: manage_students.php");
?>
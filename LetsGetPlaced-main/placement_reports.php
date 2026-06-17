<?php
session_start();
include("db_connect.php");
if(!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

$year_filter = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');
$branch_filter = isset($_GET['branch']) ? $_GET['branch'] : 'All';

// Helper
function getCount($conn, $sql) {
    $result = mysqli_query($conn, $sql);
    return ($result) ? mysqli_num_rows($result) : 0;
}

// Queries with Branch Filter support
$branch_query = ($branch_filter !== 'All') ? "AND branch = '$branch_filter'" : "";

$total_students = getCount($conn, "SELECT * FROM students WHERE YEAR(created_at) = '$year_filter' $branch_query");
$total_registrations = getCount($conn, "SELECT r.* FROM registrations r JOIN students s ON r.student_id = s.student_id WHERE YEAR(s.created_at) = '$year_filter' $branch_query");
$total_selected = getCount($conn, "SELECT r.* FROM registrations r JOIN students s ON r.student_id = s.student_id WHERE YEAR(s.created_at) = '$year_filter' AND r.status = 'Selected' $branch_query");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Placement Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; font-family: 'Inter', sans-serif; }
        .stat-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; text-align: center; text-decoration: none; color: #000; transition: 0.3s; display: block; }
        .stat-card:hover { transform: translateY(-5px); border-color: #0d6efd; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <a href="admin_dashboard.php" class="btn btn-outline-secondary">&larr; Dashboard</a>
        <h2 class="fw-bold">Placement Reports</h2>
        <button onclick="window.print()" class="btn btn-primary">Print Report</button>
    </div>

    <div class="card p-3 mb-4 no-print">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <select name="year" class="form-select" onchange="this.form.submit()">
                    <?php for($y=date('Y'); $y>=2020; $y--) echo "<option value='$y' ".($year_filter==$y?'selected':'').">$y</option>"; ?>
                </select>
            </div>
            <div class="col-md-4">
                <select name="branch" class="form-select" onchange="this.form.submit()">
                    <option value="All">All Branches</option>
                    <?php
                    $branches = mysqli_query($conn, "SELECT DISTINCT branch FROM students");
                    while($b = mysqli_fetch_assoc($branches)) echo "<option value='{$b['branch']}' ".($branch_filter==$b['branch']?'selected':'').">{$b['branch']}</option>";
                    ?>
                </select>
            </div>
        </form>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-md-4">
            <a href="manage_students.php" class="stat-card"><h6>Students</h6><h3><?php echo $total_students; ?></h3></a>
        </div>
        <div class="col-md-4">
            <a href="manage_registrations.php" class="stat-card"><h6>Registrations</h6><h3><?php echo $total_registrations; ?></h3></a>
        </div>
        <div class="col-md-4">
            <a href="manage_registrations.php?status=Selected" class="stat-card border-success"><h6>Selected</h6><h3><?php echo $total_selected; ?></h3></a>
        </div>
    </div>
</div>

</body>
</html>
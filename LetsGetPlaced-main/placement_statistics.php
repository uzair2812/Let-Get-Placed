<?php
session_start();
include("db_connect.php");

if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$total_students = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM students"));
$total_companies = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM companies"));
$total_selected = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM registrations WHERE status='Selected'"));
$total_registered = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM registrations"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Placement Statistics | Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { margin: 0; background-color: #f1f5f9; font-family: 'Inter', sans-serif; color: #334155; }
        .sidebar { width: 260px; height: 100vh; background-color: #0f172a; position: fixed; border-right: 1px solid #1e293b; display: flex; flex-direction: column; }
        .sidebar-brand { font-size: 1.2rem; font-weight: 700; color: #ffffff; padding: 24px 20px; border-bottom: 1px solid #1e293b; }
        .sidebar a { display: block; padding: 14px 20px; color: #94a3b8; text-decoration: none; font-size: 0.9rem; font-weight: 500; }
        .sidebar a:hover, .sidebar a.active { background-color: #1e293b; color: #ffffff; }
        .sidebar .mt-4 { margin-top: auto !important; border-top: 1px solid #1e293b; }
        
        .main { margin-left: 260px; padding: 40px; }
        .card-box { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.05); padding: 24px; }
        .stat-value { font-size: 2rem; font-weight: 700; color: #0f172a; }
        .stat-label { font-size: 0.85rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-brand">Let's Get Placed</div>
        <a href="admin_dashboard.php"><span>🏠</span> Dashboard</a>
        <a href="manage_students.php"><span>👨‍🎓</span> Manage Students</a>
        <a href="add_student.php"><span>➕</span> Add Student</a>
        <a href="manage_companies.php"><span>🏢</span> Manage Companies</a>
        <a href="add_company.php"><span>➕</span> Add Company</a>
        <a href="manage_registrations.php"><span>📋</span> Registrations</a>
        <a href="manage_notifications.php"><span>🔔</span> Notifications</a>
        <a href="manage_materials.php"><span>📚</span> Placement Materials</a>
        <a href="manage_tickets.php"><span>🎫</span> Support Tickets</a>
        <a href="placement_reports.php"><span>📊</span> Reports</a>
        <a href="placement_statistics.php" class="active"><span>📈</span> Statistics</a>
        <a href="logout.php" class="mt-4 text-danger"><span>🚪</span> Logout</a>
    </div>

    <div class="main">
        <h2 class="mb-4 fw-bold">Placement Statistics Overview</h2>
        
        <div class="row g-4 mb-4">
            <div class="col-md-3"><div class="card-box text-center"><div class="stat-label">Total Students</div><div class="stat-value"><?php echo $total_students; ?></div></div></div>
            <div class="col-md-3"><div class="card-box text-center"><div class="stat-label">Total Companies</div><div class="stat-value"><?php echo $total_companies; ?></div></div></div>
            <div class="col-md-3"><div class="card-box text-center"><div class="stat-label">Registered</div><div class="stat-value"><?php echo $total_registered; ?></div></div></div>
            <div class="col-md-3"><div class="card-box text-center"><div class="stat-label">Selected</div><div class="stat-value"><?php echo $total_selected; ?></div></div></div>
        </div>

        <div class="card-box">
            <h5 class="mb-4">Comparative Placement Metrics</h5>
            <canvas id="placementChart" height="80"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('placementChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Students', 'Companies', 'Registered', 'Selected'],
                datasets: [{
                    label: 'Placement Count',
                    data: [<?php echo "$total_students, $total_companies, $total_registered, $total_selected"; ?>],
                    backgroundColor: '#2563eb',
                    borderRadius: 6
                }]
            },
            options: { scales: { y: { beginAtZero: true } } }
        });
    </script>
</body>
</html>
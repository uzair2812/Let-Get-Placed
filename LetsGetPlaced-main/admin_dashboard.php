<?php
session_start();

if(!isset($_SESSION['admin_id']))
{
    header("Location: login.php");
    exit();
}

include("db_connect.php");

/* Dashboard Metric Evaluators */
$student_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM students"));
$company_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM companies"));
$registration_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM registrations"));
$notification_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM notifications"));
$selected_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM registrations WHERE status='Selected'"));
$shortlisted_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM registrations WHERE status='Shortlisted'"));
$ticket_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tickets"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Control Center | Let's Get Placed</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
            color: #333;
        }
        
        /* Executive Side Panel Framework */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: #0f172a; 
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            z-index: 100;
            overflow-y: auto;
        }
        .sidebar-brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.25rem;
            padding: 15px 24px;
            letter-spacing: -0.5px;
            border-bottom: 1px solid #1e293b;
            margin-bottom: 15px;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 11px 24px;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #1e293b;
            color: #fff;
        }
        .sidebar a span {
            margin-right: 12px;
            font-size: 1.05rem;
        }
        
        /* Workspace Framework */
        .main-content {
            margin-left: 260px;
            padding: 40px;
        }
        
        /* Clickable Interactive Stat Cards */
        .stat-card-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .stat-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            transition: all 0.2s ease;
            position: relative;
        }
        .stat-card-link:hover .stat-card {
            transform: translateY(-3px);
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
        }
        .stat-title {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stat-card-link:hover .stat-title {
            color: #0f172a;
        }
        .stat-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }
        .card-arrow {
            font-size: 0.75rem;
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        .stat-card-link:hover .card-arrow {
            opacity: 0.5;
        }
        
        /* Structured Containers */
        .content-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            margin-bottom: 24px;
            overflow: hidden;
        }
        .card-header-custom {
            padding: 18px 24px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-header-custom h5 {
            margin: 0;
            font-weight: 600;
            color: #1e293b;
            font-size: 1.05rem;
        }
        
        /* High-Definition Tables */
        .table-custom {
            margin-bottom: 0;
            vertical-align: middle;
        }
        .table-custom th {
            background-color: #f8f9fa;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.72rem;
            letter-spacing: 0.5px;
            padding: 12px 24px;
            border-bottom: 1px solid #e2e8f0;
        }
        .table-custom td {
            padding: 14px 24px;
            color: #334155;
            font-size: 0.88rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .table-custom tr:last-child td {
            border-bottom: none;
        }

        /* Subtle Context Badges */
        .badge-danger-subtle {
            background-color: #fef2f2;
            color: #ef4444;
            border: 1px solid #fee2e2;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-brand">Let's Get Placed</div>
        <a href="admin_dashboard.php" class="active"><span>🏠</span> Dashboard</a>
        <a href="manage_students.php"><span>👨‍🎓</span> Manage Students</a>
        <a href="add_student.php"><span>➕</span> Add Student</a>
        <a href="manage_companies.php"><span>🏢</span> Manage Companies</a>
        <a href="add_company.php"><span>➕</span> Add Company</a>
        <a href="manage_registrations.php"><span>📋</span> Registrations</a>
        <a href="manage_notifications.php"><span>🔔</span> Notifications</a>
        <a href="manage_materials.php"><span>📚</span> Placement Materials</a>
        <a href="manage_tickets.php"><span>🎫</span> Support Tickets</a>
        <a href="placement_reports.php"><span>📊</span> Reports</a>
        <a href="placement_statistics.php"><span>📈</span> Statistics</a>
        <a href="logout.php" class="mt-4 text-danger"><span>🚪</span> Logout</a>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1" style="color: #0f172a;">Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></h1>
                <p class="text-muted m-0">Placement Officer Dashboard & Operational Overview</p>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4 col-xl-2">
                <a href="manage_students.php" class="stat-card-link">
                    <div class="stat-card">
                        <div class="stat-title">Students <span class="card-arrow">↗</span></div>
                        <div class="stat-value"><?php echo $student_count; ?></div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <a href="manage_companies.php" class="stat-card-link">
                    <div class="stat-card">
                        <div class="stat-title">Companies <span class="card-arrow">↗</span></div>
                        <div class="stat-value"><?php echo $company_count; ?></div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <a href="manage_registrations.php" class="stat-card-link">
                    <div class="stat-card">
                        <div class="stat-title">Applications <span class="card-arrow">↗</span></div>
                        <div class="stat-value"><?php echo $registration_count; ?></div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <a href="manage_registrations.php?filter=Selected" class="stat-card-link">
                    <div class="stat-card">
                        <div class="stat-title">Selected <span class="card-arrow">↗</span></div>
                        <div class="stat-value text-success"><?php echo $selected_count; ?></div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <a href="manage_registrations.php?filter=Shortlisted" class="stat-card-link">
                    <div class="stat-card">
                        <div class="stat-title">Shortlisted <span class="card-arrow">↗</span></div>
                        <div class="stat-value text-warning"><?php echo $shortlisted_count; ?></div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <a href="manage_tickets.php" class="stat-card-link">
                    <div class="stat-card">
                        <div class="stat-title">Open Tickets <span class="card-arrow">↗</span></div>
                        <div class="stat-value text-danger"><?php echo $ticket_count; ?></div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-6">
                <div class="content-card">
                    <div class="card-header-custom">
                        <h5>Recently Drafted Corporate Calendars</h5>
                        <a href="manage_companies.php" class="btn btn-link btn-sm text-decoration-none p-0">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Corporate Entity</th>
                                    <th>Role Profile</th>
                                    <th>Compensation</th>
                                    <th>Drive Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $companies_query = mysqli_query($conn, "SELECT * FROM companies ORDER BY company_id DESC LIMIT 3");
                                if(mysqli_num_rows($companies_query) > 0) {
                                    while($row = mysqli_fetch_assoc($companies_query)) {
                                ?>
                                    <tr>
                                        <td class="fw-semibold text-dark"><?php echo htmlspecialchars($row['company_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['role_offered']); ?></td>
                                        <td><span class="fw-semibold text-success">₹<?php echo htmlspecialchars($row['ctc']); ?> LPA</span></td>
                                        <td class="text-muted"><?php echo date('M d, Y', strtotime($row['drive_date'])); ?></td>
                                    </tr>
                                <?php 
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center text-muted py-3'>No companies configured yet.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="content-card">
                    <div class="card-header-custom">
                        <h5>Pending Support Tickets Queue</h5>
                        <a href="manage_tickets.php" class="btn btn-link btn-sm text-decoration-none p-0">Review Board</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-custom">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Issue Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $tickets_query = mysqli_query($conn, "SELECT t.*, s.full_name FROM tickets t JOIN students s ON t.student_id = s.student_id ORDER BY t.ticket_id DESC LIMIT 3");
                                if(mysqli_num_rows($tickets_query) > 0) {
                                    while($ticket = mysqli_fetch_assoc($tickets_query)) {
                                ?>
                                    <tr>
                                        <td>
                                            <span class="fw-semibold text-dark d-block"><?php echo htmlspecialchars($ticket['full_name']); ?></span>
                                            <small class="text-muted">ID: <?php echo htmlspecialchars($ticket['student_id']); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge badge-danger-subtle px-2 py-1.5 rounded text-wrap" style="font-size: 0.75rem;">
                                                <?php echo htmlspecialchars($ticket['ticket_reason']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="manage_tickets.php" class="btn btn-light btn-sm border fw-medium">View</a>
                                        </td>
                                    </tr>
                                <?php 
                                    }
                                } else {
                                    echo "<tr><td colspan='3' class='text-center text-muted py-4'>✓ All operations stable. Zero pending tickets.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
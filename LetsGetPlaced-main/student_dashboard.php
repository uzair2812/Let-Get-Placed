<?php
session_start();

if(!isset($_SESSION['student_id']))
{
    header("Location: student_login.php");
    exit();
}

include("db_connect.php");

$student_id = $_SESSION['student_id'];

$student = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM students WHERE student_id='$student_id'")
);

$companies = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM companies"));
$notifications = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM notifications"));
$registrations = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM registrations WHERE student_id='$student_id'"));
$tickets = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tickets WHERE student_id='$student_id'"));

$resume_status = !empty($student['resume_file']) ? "Uploaded" : "Not Uploaded";

$profile_complete = 0;
if(!empty($student['phone'])) $profile_complete += 10;
if(!empty($student['address'])) $profile_complete += 10;
if(!empty($student['dob'])) $profile_complete += 10;
if(!empty($student['gender'])) $profile_complete += 10;
if(!empty($student['sslc_percentage'])) $profile_complete += 15;
if(!empty($student['puc_percentage'])) $profile_complete += 15;
if(!empty($student['cgpa'])) $profile_complete += 15;
if(!empty($student['resume_file'])) $profile_complete += 15;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard | Let's Get Placed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts for cleaner look -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
            color: #333;
        }
        /* Fixed elegant sidebar */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: #0f172a; /* Sophisticated Dark Slate */
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            z-index: 100;
        }
        .sidebar-brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.25rem;
            padding: 15px 24px;
            letter-spacing: -0.5px;
            border-bottom: 1px solid #1e293b;
            margin-bottom: 20px;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 12px 24px;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #1e293b;
            color: #fff;
        }
        .sidebar a span {
            margin-right: 12px;
            font-size: 1.1rem;
        }
        /* Main Layout Content window */
        .main-content {
            margin-left: 260px;
            padding: 40px;
        }
        /* Minimalist Modern Cards */
        .stat-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .stat-title {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 8px;
        }
        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }
        .content-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            margin-bottom: 24px;
        }
        .card-header-custom {
            padding: 20px 24px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .card-header-custom h5 {
            margin: 0;
            font-weight: 600;
            color: #1e293b;
        }
        /* Premium Table Design */
        .table-custom {
            margin-bottom: 0;
        }
        .table-custom th {
            background-color: #f8f9fa;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 14px 24px;
            border-bottom: 1px solid #e2e8f0;
        }
        .table-custom td {
            padding: 16px 24px;
            vertical-align: middle;
            color: #334155;
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f5f9;
        }
        /* Cleaned Pill Badges */
        .badge-custom {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

    <!-- Sidebar Section Layout -->
    <div class="sidebar">
        <div class="sidebar-brand">Let's Get Placed</div>
        <a href="student_dashboard.php" class="active"><span>🏠</span> Dashboard</a>
        <a href="student_profile.php"><span>👤</span> My Profile</a>
        <a href="upload_resume.php"><span>📄</span> My Resume</a>
        <a href="resume_builder.php"><span>📝</span> Resume Builder</a>
        <a href="companies.php"><span>🏢</span> Companies</a>
        <a href="registered_drives.php"><span>📋</span> Registered Drives</a>
        <a href="placement_materials.php"><span>📚</span> Materials</a>
        <a href="notifications.php"><span>🔔</span> Notifications</a>
        <a href="raise_tickets.php"><span>🎫</span> Raise Ticket</a>
        <a href="logout.php" class="mt-5 text-danger"><span>🚪</span> Logout</a>
    </div>

    <!-- Main Working Panel -->
    <div class="main-content">
        <!-- Top welcoming dynamic display -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1" style="color: #0f172a;">Welcome, <?php echo htmlspecialchars($student['full_name']); ?></h1>
                <p class="text-muted m-0">Branch: <?php echo htmlspecialchars($student['branch']); ?> | Dashboard Overview</p>
            </div>
            <div>
                <a href="raise_tickets.php" class="btn btn-outline-danger btn-sm px-3 fw-medium">Raise Support Request</a>
            </div>
        </div>

        <!-- Metrics Grid Container (No longer clustered or uneven) -->
        <div class="row g-3 mb-4">
            <div class="col">
                <div class="stat-card">
                    <div class="stat-title">Companies</div>
                    <div class="stat-value"><?php echo $companies; ?></div>
                </div>
            </div>
            <div class="col">
                <div class="stat-card">
                    <div class="stat-title">Notifications</div>
                    <div class="stat-value"><?php echo $notifications; ?></div>
                </div>
            </div>
            <div class="col">
                <div class="stat-card">
                    <div class="stat-title">Drives Joined</div>
                    <div class="stat-value"><?php echo $registrations; ?></div>
                </div>
            </div>
            <div class="col">
                <div class="stat-card">
                    <div class="stat-title">Tickets Open</div>
                    <div class="stat-value"><?php echo $tickets; ?></div>
                </div>
            </div>
            <div class="col">
                <div class="stat-card">
                    <div class="stat-title">Profile Integrity</div>
                    <div class="stat-value text-primary"><?php echo $profile_complete; ?>%</div>
                </div>
            </div>
        </div>

        <!-- Main Body Row Configurations -->
        <div class="row">
            <!-- Left Data Column: Summary & Management -->
            <div class="col-lg-5">
                <div class="content-card">
                    <div class="card-header-custom">
                        <h5>Student Dossier</h5>
                    </div>
                    <div class="p-0">
                        <table class="table table-custom">
                            <tr>
                                <td class="fw-medium text-muted" style="width: 35%;">USN</td>
                                <td class="fw-semibold"><?php echo htmlspecialchars($student['usn']); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-medium text-muted">Academic Stream</td>
                                <td><?php echo htmlspecialchars($student['branch']); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-medium text-muted">Current CGPA</td>
                                <td class="fw-bold text-success"><?php echo htmlspecialchars($student['cgpa']); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-medium text-muted">Resume File</td>
                                <td>
                                    <?php if($resume_status === "Uploaded") { ?>
                                        <span class="badge bg-success-subtle text-success badge-custom">Active</span>
                                    <?php } else { ?>
                                        <span class="badge bg-danger-subtle text-danger badge-custom">Missing</span>
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Balanced Clean Action Center to replace ugly blocks of buttons -->
                <div class="content-card">
                    <div class="card-header-custom">
                        <h5>Document Actions</h5>
                    </div>
                    <div class="card-body text-center py-4">
                        <p class="text-muted small mb-3">Keep your compliance documents updated to ensure flawless placement verification.</p>
                        <?php if(!empty($student['resume_file'])) { ?>
                            <div class="d-grid gap-2">
                                <a href="uploads/resumes/<?php echo $student['resume_file']; ?>" target="_blank" class="btn btn-primary fw-medium btn-sm py-2">View Registered Resume</a>
                                <a href="upload_resume.php" class="btn btn-light text-secondary btn-sm border py-2">Update Workspace File</a>
                            </div>
                        <?php } else { ?>
                            <a href="upload_resume.php" class="btn btn-warning text-dark w-100 fw-bold py-2">⚠️ Upload Resume Immediately</a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <!-- Right Column: Live Schedules and Placement Actions -->
            <div class="col-lg-7">
                <div class="content-card">
                    <div class="card-header-custom">
                        <h5>Upcoming Corporate Recruitment Calendars</h5>
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
                                $result = mysqli_query($conn, "SELECT * FROM companies ORDER BY drive_date ASC LIMIT 5");
                                if(mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)) {
                                ?>
                                    <tr>
                                        <td class="fw-semibold text-dark"><?php echo htmlspecialchars($row['company_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['role_offered']); ?></td>
                                        <td><span class="fw-medium text-success"><?php echo htmlspecialchars($row['ctc']); ?> LPA</span></td>
                                        <td class="text-muted"><?php echo date('M d, Y', strtotime($row['drive_date'])); ?></td>
                                    </tr>
                                <?php 
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center text-muted py-4'>No corporate drives scheduled right now.</td></tr>";
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
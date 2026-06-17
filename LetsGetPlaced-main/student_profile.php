<?php
session_start();

if(!isset($_SESSION['student_id']))
{
    header("Location: student_login.php");
    exit();
}

include("db_connect.php");

$student_id = $_SESSION['student_id'];

$result = mysqli_query(
$conn,
"SELECT * FROM students
WHERE student_id='$student_id'"
);

$student = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Let's Get Placed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
            color: #333;
        }
        /* Persistent Slate Sidebar */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: #0f172a;
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
        /* Main Layout Frame */
        .main-content {
            margin-left: 260px;
            padding: 40px;
        }
        /* Profile Header Banner */
        .profile-hero {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            border-radius: 16px;
            padding: 32px;
            color: white;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.15);
            margin-bottom: 30px;
        }
        /* Content Containers */
        .profile-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            margin-bottom: 24px;
            height: 100%;
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
        /* Form-like data rows */
        .data-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .data-value {
            font-size: 0.95rem;
            font-weight: 500;
            color: #1e293b;
            margin-bottom: 0;
        }
        /* Mini Metrics inside academic tabs */
        .academic-box {
            background: #f8f9fa;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            text-align: center;
        }
        .academic-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 6px;
        }
    </style>
</head>
<body>

    <!-- Sidebar Layout Navigation -->
    <div class="sidebar">
        <div class="sidebar-brand">Let's Get Placed</div>
        <a href="student_dashboard.php"><span>🏠</span> Dashboard</a>
        <a href="student_profile.php" class="active"><span>👤</span> My Profile</a>
        <a href="upload_resume.php"><span>📄</span> My Resume</a>
        <a href="resume_builder.php"><span>📝</span> Resume Builder</a>
        <a href="companies.php"><span>🏢</span> Companies</a>
        <a href="registered_drives.php"><span>📋</span> Registered Drives</a>
        <a href="placement_materials.php"><span>📚</span> Materials</a>
        <a href="notifications.php"><span>🔔</span> Notifications</a>
        <a href="raise_tickets.php"><span>🎫</span> Raise Ticket</a>
        <a href="logout.php" class="mt-5 text-danger"><span>🚪</span> Logout</a>
    </div>

    <!-- Main Workspace -->
    <div class="main-content">
        
        <!-- Premium Header Banner -->
        <div class="profile-hero d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1"><?php echo htmlspecialchars($student['full_name']); ?></h2>
                <p class="opacity-75 mb-0">USN: <?php echo htmlspecialchars($student['usn']); ?> | Academic Verification File</p>
            </div>
            <div>
                <a href="student_dashboard.php" class="btn btn-light btn-sm px-4 fw-medium text-primary">
                    ← Back to Dashboard
                </a>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Grid: Personal Credentials -->
            <div class="col-lg-7">
                <div class="profile-card">
                    <div class="card-header-custom">
                        <h5>Identity & Contact Records</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="data-label">Official Email</div>
                                <p class="data-value"><?php echo htmlspecialchars($student['email']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <div class="data-label">Mobile Number</div>
                                <p class="data-value"><?php echo htmlspecialchars($student['phone']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <div class="data-label">Department / Branch</div>
                                <p class="data-value"><?php echo htmlspecialchars($student['branch']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <div class="data-label">Current Semester</div>
                                <p class="data-value"><?php echo htmlspecialchars($student['semester']); ?>th Sem</p>
                            </div>
                            <div class="col-md-6">
                                <div class="data-label">Date of Birth</div>
                                <p class="data-value"><?php echo !empty($student['dob']) ? date('M d, Y', strtotime($student['dob'])) : '—'; ?></p>
                            </div>
                            <div class="col-md-6">
                                <div class="data-label">Gender Orientation</div>
                                <p class="data-value"><?php echo htmlspecialchars($student['gender']); ?></p>
                            </div>
                            <div class="col-12">
                                <div class="data-label">Permanent Communication Address</div>
                                <p class="data-value text-secondary" style="line-height: 1.5;"><?php echo htmlspecialchars($student['address']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Grid: Academic History & Documents -->
            <div class="col-lg-5">
                <div class="d-flex flex-column gap-4">
                    
                    <!-- Performance Index Boxes -->
                    <div class="profile-card">
                        <div class="card-header-custom">
                            <h5>Academic Milestones</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-4">
                                    <div class="academic-box">
                                        <div class="academic-title">SSLC</div>
                                        <h5 class="fw-bold text-dark m-0"><?php echo htmlspecialchars($student['sslc_percentage']); ?>%</h5>
                                        <small class="text-muted text-xs">Year: <?php echo htmlspecialchars($student['sslc_year']); ?></small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="academic-box">
                                        <div class="academic-title">PUC/Dip</div>
                                        <h5 class="fw-bold text-dark m-0"><?php echo htmlspecialchars($student['puc_percentage']); ?>%</h5>
                                        <small class="text-muted text-xs">Year: <?php echo htmlspecialchars($student['puc_year']); ?></small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="academic-box">
                                        <div class="academic-title">BE/BTech</div>
                                        <h5 class="fw-bold text-primary m-0"><?php echo htmlspecialchars($student['cgpa']); ?></h5>
                                        <small class="text-muted text-xs">CGPA</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Career Objectives -->
                    <div class="profile-card">
                        <div class="card-header-custom">
                            <h5>Career Objective</h5>
                        </div>
                        <div class="card-body p-4">
                            <p class="text-muted m-0 small" style="line-height: 1.6; font-style: italic;">
                                "<?php echo htmlspecialchars($student['career_objective']); ?>"
                            </p>
                        </div>
                    </div>

                    <!-- Verified Attachements -->
                    <div class="profile-card">
                        <div class="card-header-custom">
                            <h5>Verified Documents</h5>
                        </div>
                        <div class="card-body p-4 text-center">
                            <?php if(!empty($student['resume_file'])) { ?>
                                <div class="p-3 border rounded border-success-subtle bg-success-subtle bg-opacity-10 mb-3 d-flex align-items-center justify-content-between text-start">
                                    <div>
                                        <span class="fs-4 me-2">📄</span>
                                        <span class="fw-medium text-success small">Resume Verified</span>
                                    </div>
                                    <a href="uploads/resumes/<?php echo $student['resume_file']; ?>" target="_blank" class="btn btn-success btn-sm fw-medium px-3">
                                        View File
                                    </a>
                                </div>
                            <?php } else { ?>
                                <div class="alert alert-warning p-3 rounded mb-0 text-start small d-flex align-items-center justify-content-between">
                                    <span>⚠️ No active resume found on workspace.</span>
                                    <a href="upload_resume.php" class="btn btn-warning btn-sm fw-bold">Upload</a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
session_start();
include("db_connect.php");

// Strict Security Verification Gateway
if(!isset($_SESSION['admin_id']))
{
    header("Location: login.php");
    exit();
}

$message = "";
$message_type = "info"; // Used to control dynamic styling properties of structural feedback alerts

if(isset($_POST['save']))
{
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $usn = mysqli_real_escape_string($conn, trim($_POST['usn']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $branch = mysqli_real_escape_string($conn, trim($_POST['branch']));

    // 🔐 Secure password hashing engine alignment
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $sslc_percentage = mysqli_real_escape_string($conn, $_POST['sslc_percentage']);
    $sslc_year = mysqli_real_escape_string($conn, $_POST['sslc_year']);

    $puc_percentage = mysqli_real_escape_string($conn, $_POST['puc_percentage']);
    $puc_year = mysqli_real_escape_string($conn, $_POST['puc_year']);

    $cgpa = mysqli_real_escape_string($conn, $_POST['cgpa']);
    $semester = mysqli_real_escape_string($conn, $_POST['semester']);

    $career_objective = mysqli_real_escape_string($conn, $_POST['career_objective']);

    $resume = "";

    // 📄 File Handling and Security Verification Block
    if(isset($_FILES['resume']) && $_FILES['resume']['name'] != "")
    {
        $allowed = ['pdf','jpg','jpeg','png'];

        $file_name = $_FILES['resume']['name'];
        $tmp = $_FILES['resume']['tmp_name'];

        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if(in_array($ext, $allowed))
        {
            $target_dir = "uploads/resumes";
            if(!file_exists($target_dir))
            {
                mkdir($target_dir, 0777, true);
            }

            $resume = time() . '_' . rand(1000, 9999) . '.' . $ext;
            move_uploaded_file($tmp, $target_dir . "/" . $resume);
        }
        else
        {
            $message = "❌ Invalid file structure context. Only PDF, JPG, JPEG, and PNG targets are allowed.";
            $message_type = "danger";
        }
    }

    if($message == "")
    {
        $check = mysqli_query($conn, "SELECT * FROM students WHERE usn='$usn'");

        if(mysqli_num_rows($check) > 0)
        {
            $message = "⚠️ Structural collision: A profile with this USN unique key identifier already exists.";
            $message_type = "warning";
        }
        else
        {
            $insert = mysqli_query($conn, "
                INSERT INTO students (
                    full_name, usn, email, phone, branch, password, dob, gender, address, 
                    sslc_percentage, sslc_year, puc_percentage, puc_year, cgpa, semester, 
                    career_objective, resume_file
                ) VALUES (
                    '$name', '$usn', '$email', '$phone', '$branch', '$password', '$dob', '$gender', '$address', 
                    '$sslc_percentage', '$sslc_year', '$puc_percentage', '$puc_year', '$cgpa', '$semester', 
                    '$career_objective', '$resume'
                )
            ");

            if($insert)
            {
                $message = "✔ Academic student profile entry built and recorded cleanly into relational clusters.";
                $message_type = "success";
            }
            else
            {
                $message = "❌ Database Error Exception: " . mysqli_error($conn);
                $message_type = "danger";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provision Student Profile | Administrative Dashboard</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
            color: #334155;
            padding: 40px;
        }
        .admin-container {
            max-width: 950px;
            margin: 0 auto;
        }
        /* Administrative Panel Card Frame */
        .admin-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.03);
            overflow: hidden;
        }
        .card-header-custom {
            background-color: #0f172a; /* Deep Charcoal Slate Administrative Theme Anchor */
            padding: 24px 35px;
            border-bottom: 1px solid #1e293b;
        }
        .card-header-custom h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
            letter-spacing: -0.5px;
        }
        
        /* Modern Inputs Layout Style Mapping Admin Portal Framework */
        .section-title {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.75px;
            color: #0f172a;
            background-color: #f8fafc;
            padding: 10px 16px;
            border-left: 4px solid #0f172a;
            border-radius: 0 4px 4px 0;
            margin-top: 32px;
            margin-bottom: 20px;
        }
        .form-label-custom {
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
            margin-bottom: 6px;
        }
        .form-control-custom {
            border: 1px solid #e2e8f0;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #0f172a;
            transition: all 0.2s ease;
        }
        .form-control-custom:focus {
            outline: none;
            border-color: #0f172a;
            box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.05);
        }
        
        /* Action Elements configuration */
        .btn-admin-primary {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 12px 28px;
            border-radius: 8px;
            border: none;
            transition: background 0.2s ease;
        }
        .btn-admin-primary:hover {
            background-color: #1e293b;
            color: #ffffff;
        }
        .btn-admin-secondary {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 12px 24px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-block;
        }
        .btn-admin-secondary:hover {
            background-color: #e2e8f0;
            color: #0f172a;
        }
    </style>
</head>
<body>

    <div class="admin-container">
        
        <div class="mb-4">
            <a href="manage_students.php" class="text-decoration-none text-muted small fw-medium">← Back to Records Management</a>
        </div>

        <div class="card admin-card">
            <div class="card-header-custom">
                <h2>Create Student Dossier Profile</h2>
            </div>

            <div class="card-body p-5 bg-white">
                
                <?php if(!empty($message)) { 
                    $alert_bg = ($message_type == 'success') ? '#f0fdf4' : (($message_type == 'danger') ? '#fef2f2' : '#fffbeb');
                    $alert_color = ($message_type == 'success') ? '#166534' : (($message_type == 'danger') ? '#991b1b' : '#9a3412');
                ?>
                    <div class="alert border-0 p-3 small mb-4" style="background-color: <?php echo $alert_bg; ?>; color: <?php echo $alert_color; ?>; border-radius: 8px;">
                        <?php echo $message; ?>
                    </div>
                <?php } ?>

                <form method="POST" enctype="multipart/form-data" autocomplete="off">
                    
                    <div class="section-title" style="margin-top: 0;">1. Core Matrix Information</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Full Legal Name</label>
                            <input type="text" name="name" class="form-control form-control-custom" placeholder="e.g., Johnathan Doe" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">University Seat Number (USN)</label>
                            <input type="text" name="usn" class="form-control form-control-custom text-uppercase" placeholder="e.g., 1UV22CS000" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label-custom">Primary Institutional Email</label>
                            <input type="email" name="email" class="form-control form-control-custom" placeholder="e.g., student@university.edu" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Contact Phone Number</label>
                            <input type="text" name="phone" class="form-control form-control-custom" placeholder="e.g., +91 9876543210" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Default Workspace Password</label>
                            <input type="password" name="password" class="form-control form-control-custom" placeholder="🔑 System Token Set" required>
                        </div>
                    </div>

                    <div class="section-title">2. Demographics & Personal Record</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label-custom">Date of Birth</label>
                            <input type="date" name="dob" class="form-control form-control-custom">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Gender Specification</label>
                            <select name="gender" class="form-select form-control-custom">
                                <option value="">Select gender profile...</option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Assigned Department Branch</label>
                            <input type="text" name="branch" class="form-control form-control-custom text-uppercase" placeholder="e.g., CSE / ISE" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Permanent Residential Address</label>
                        <textarea name="address" class="form-control form-control-custom" rows="2" placeholder="Complete structural postal address data..."></textarea>
                    </div>

                    <div class="section-title">3. Historical Academic Metrics</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-3">
                            <label class="form-label-custom">SSLC Percentage (%)</label>
                            <input type="number" step="0.01" name="sslc_percentage" class="form-control form-control-custom" placeholder="e.g., 92.50" min="0" max="100">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">SSLC Passing Year</label>
                            <input type="number" name="sslc_year" class="form-control form-control-custom" placeholder="e.g., 2020">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">PUC / Diploma (%)</label>
                            <input type="number" step="0.01" name="puc_percentage" class="form-control form-control-custom" placeholder="e.g., 88.40" min="0" max="100">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">PUC Passing Year</label>
                            <input type="number" name="puc_year" class="form-control form-control-custom" placeholder="e.g., 2022">
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Current Cumulative CGPA</label>
                            <input type="number" step="0.01" name="cgpa" class="form-control form-control-custom" placeholder="e.g., 8.75" min="0" max="10">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Current Term Semester</label>
                            <input type="number" name="semester" class="form-control form-control-custom" placeholder="e.g., 6" min="1" max="8">
                        </div>
                    </div>

                    <div class="section-title">4. Placement Artifacts</div>
                    <div class="mb-4">
                        <label class="form-label-custom">Career Objective Statement</label>
                        <textarea name="career_objective" class="form-control form-control-custom" rows="3" placeholder="Summarize professional ambitions and alignment goals..."></textarea>
                    </div>
                    <div class="mb-5">
                        <label class="form-label-custom">Verified Resume Ledger File</label>
                        <input type="file" name="resume" class="form-control form-control-custom">
                        <div class="form-text text-muted mt-2" style="font-size: 0.8rem;">
                            Accepted structural file formats: <span class="font-monospace text-dark">.pdf, .png, .jpg, .jpeg</span>
                        </div>
                    </div>

                    <div class="pt-3 border-top border-light-subtle d-flex gap-2">
                        <button type="submit" name="save" class="btn btn-admin-primary shadow-sm">
                            Commit & Save Record
                        </button>
                        <a href="manage_students.php" class="btn btn-admin-secondary">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
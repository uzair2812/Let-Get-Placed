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
$message_type = "info"; // Controls the visual identity state of response toast containers

if(isset($_POST['save_company']))
{
    // Escaping input parameters to safeguard against SQL Injection vulnerabilities
    $company_name = mysqli_real_escape_string($conn, trim($_POST['company_name']));
    $role         = mysqli_real_escape_string($conn, trim($_POST['role']));
    $ctc          = mysqli_real_escape_string($conn, trim($_POST['ctc']));
    $drive_date   = mysqli_real_escape_string($conn, trim($_POST['drive_date']));
    $cgpa         = mysqli_real_escape_string($conn, trim($_POST['cgpa']));
    $description  = mysqli_real_escape_string($conn, trim($_POST['company_desc']));

    $jd_file = "";

    // 📄 Job Description File upload logic with explicit PDF containment restrictions
    if(isset($_FILES['jd_pdf']) && $_FILES['jd_pdf']['name'] != "")
    {
        $file_name = $_FILES['jd_pdf']['name'];
        $tmp_name  = $_FILES['jd_pdf']['tmp_name'];
        
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Strict Enforcement: Only Job Description PDFs are authorized to proceed
        if($ext === 'pdf')
        {
            $target_dir = "uploads/jd";
            if(!file_exists($target_dir))
            {
                mkdir($target_dir, 0777, true);
            }

            // Create a randomized tokenized filename to eliminate risk of override collisions
            $jd_file = time() . '_jd_' . rand(1000, 9999) . '.pdf';
            move_uploaded_file($tmp_name, $target_dir . "/" . $jd_file);
        }
        else
        {
            $message = "❌ Access Denied: The document selection parameter is unauthorized. You must upload a valid format execution profile (.pdf extension).";
            $message_type = "danger";
        }
    }

    // Process persistence block if validation checks clear cleanly
    if($message == "")
    {
        $sql = "INSERT INTO companies (company_name, role_offered, ctc, drive_date, eligibility_cgpa, company_desc, jd_file)
                VALUES ('$company_name', '$role', '$ctc', '$drive_date', '$cgpa', '$description', '$jd_file')";

        if(mysqli_query($conn, $sql))
        {
            $message = "✔ Corporate recruitment campaign profile compiled and active inside the metrics registry.";
            $message_type = "success";
        }
        else
        {
            $message = "❌ Database Exception Error: Failure saving campaign record lines: " . mysqli_error($conn);
            $message_type = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provision Corporate Drive | Administrative Terminal</title>
    
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
            <a href="manage_companies.php" class="text-decoration-none text-muted small fw-medium">← Back to Company Dashboard Matrix</a>
        </div>

        <div class="card admin-card">
            <div class="card-header-custom">
                <h2>Initialize Placement Drive Profiling</h2>
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
                    
                    <div class="section-title" style="margin-top: 0;">1. Corporate Entity Metrics</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Corporate Legal Name</label>
                            <input type="text" name="company_name" class="form-control form-control-custom" placeholder="e.g., Microsoft Cloud" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Target Role Designation</label>
                            <input type="text" name="role" class="form-control form-control-custom" placeholder="e.g., Associate DevOps Architect" required>
                        </div>
                    </div>

                    <div class="section-title">2. Financial Index & Eligibility Demarcation</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label-custom">Financial Package Offer (CTC in LPA)</label>
                            <input type="number" step="0.01" name="ctc" class="form-control form-control-custom" placeholder="e.g., 14.50" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Target Drive Calendar Date</label>
                            <input type="date" name="drive_date" class="form-control form-control-custom" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Academic Cutoff Threshold (CGPA)</label>
                            <input type="number" step="0.01" name="cgpa" class="form-control form-control-custom" placeholder="e.g., 7.50" min="0" max="10" required>
                        </div>
                    </div>

                    <div class="section-title">3. Structural Artifacts & Details</div>
                    <div class="mb-4">
                        <label class="form-label-custom">Company Profile Overview & Processing Details</label>
                        <textarea name="company_desc" class="form-control form-control-custom" rows="4" placeholder="Outline specific structural parameters, registration rules, selection rounds, background info, or processing bond instructions..."></textarea>
                    </div>

                    <div class="mb-5">
                        <label class="form-label-custom">Verified Job Description Document (PDF File Upload Only)</label>
                        <input type="file" name="jd_pdf" class="form-control form-control-custom" accept=".pdf" required>
                        <div class="form-text text-muted mt-2" style="font-size: 0.8rem;">
                            Accepted structural secure system configuration targets: <span class="font-monospace text-dark">.pdf format only</span>
                        </div>
                    </div>

                    <div class="pt-3 border-top border-light-subtle d-flex gap-2">
                        <button type="submit" name="save_company" class="btn btn-admin-primary shadow-sm">
                            Commit & Deploy Drive Registry
                        </button>
                        <a href="manage_companies.php" class="btn btn-admin-secondary">
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
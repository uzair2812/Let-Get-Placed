<?php
session_start();
include("db_connect.php");

// 1. Security Check
if(!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$company_id = intval($_GET['id']);

// 2. Handle Application Submission
if(isset($_POST['confirm_application'])) {
    // Re-verify resume exists
    $check_res = mysqli_query($conn, "SELECT resume_file FROM students WHERE student_id='$student_id'");
    $check_row = mysqli_fetch_assoc($check_res);
    
    if(!empty($check_row['resume_file'])) {
        // Insert application
        $insert_query = "INSERT INTO registrations (student_id, company_id) VALUES ('$student_id', '$company_id')";
        if(mysqli_query($conn, $insert_query)) {
            header("Location: registered_drives.php");
            exit();
        } else {
            $error = "Error: You may have already applied for this drive.";
        }
    }
}

// 3. Handle Isolated Resume Upload
$upload_message = "";
if(isset($_POST['upload_now'])) {
    $target_dir = "uploads/resumes/";
    if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }
    
    $file_name = time() . "_" . basename($_FILES["resume_file"]["name"]);
    $target_file = $target_dir . $file_name;
    
    if(strtolower(pathinfo($target_file, PATHINFO_EXTENSION)) == "pdf") {
        if(move_uploaded_file($_FILES["resume_file"]["tmp_name"], $target_file)) {
            mysqli_query($conn, "UPDATE students SET resume_file='$file_name' WHERE student_id='$student_id'");
            $upload_message = "<div class='alert alert-success p-2 small'>Resume uploaded.</div>";
        }
    } else {
        $upload_message = "<div class='alert alert-danger p-2 small'>Only PDF allowed.</div>";
    }
}

// 4. Fetch Data
$query = mysqli_query($conn, "SELECT * FROM companies WHERE company_id='$company_id'");
$row = mysqli_fetch_assoc($query);

$s_query = mysqli_query($conn, "SELECT resume_file FROM students WHERE student_id='$student_id'");
$s_row = mysqli_fetch_assoc($s_query);
$has_resume = !empty($s_row['resume_file']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profile | <?php echo htmlspecialchars($row['company_name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; font-family: sans-serif; padding: 40px; }
        .card-custom { border-radius: 12px; }
        .panel-header { font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 700; margin-bottom: 8px; }
    </style>
</head>
<body>
<div class="container" style="max-width: 950px;">
    <div class="card card-custom p-4 bg-white shadow-sm">
        <h2 class="fw-bold mb-4"><?php echo htmlspecialchars($row['company_name']); ?></h2>
        
        <div class="row">
            <div class="col-md-7">
                <div class="panel-header">Company Overview</div>
                <p class="text-muted mb-4"><?php echo nl2br(htmlspecialchars($row['company_desc'] ?? 'No description.')); ?></p>
                <div class="row">
                    <div class="col-6"><div class="panel-header">Role</div><p class="fw-bold"><?php echo htmlspecialchars($row['role_offered']); ?></p></div>
                    <div class="col-6"><div class="panel-header">Package</div><p class="fw-bold text-success"><?php echo htmlspecialchars($row['ctc']); ?> LPA</p></div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card bg-light border-0 p-3 mb-3">
                    <div class="panel-header">Corporate Job Description</div>
                    <?php if(!empty($row['jd_file']) && file_exists("uploads/jd/" . $row['jd_file'])) { ?>
                        <a href="uploads/jd/<?php echo $row['jd_file']; ?>" class="btn btn-dark btn-sm w-100" target="_blank">📥 View JD</a>
                    <?php } else { ?>
                        <p class="small text-muted mb-0">No document available.</p>
                    <?php } ?>
                </div>

                <div class="card border border-primary-subtle p-3">
                    <div class="panel-header text-primary">Resume Upload Zone</div>
                    <?php echo $upload_message; ?>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="file" name="resume_file" class="form-control form-control-sm mb-2" required accept=".pdf">
                        <button type="submit" name="upload_now" class="btn btn-outline-primary btn-sm w-100">Upload Resume</button>
                    </form>
                    <?php if($has_resume) { ?>
                        <div class="small text-success mt-2">✅ Resume active.</div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <hr class="my-4">
        
        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <button type="submit" name="confirm_application" class="btn btn-primary px-4 fw-bold" <?php echo !$has_resume ? 'disabled' : ''; ?>>
                Finalize Application
            </button>
            <a href="companies.php" class="btn btn-outline-secondary px-4">Back</a>
        </form>
    </div>
</div>
</body>
</html>
<?php
session_start();
include("db_connect.php");

// Redirect straight to dashboard if session is already initialized
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM admins WHERE username='$username' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Standard dynamic fallback check to natively accommodate both plain text and secure crypt hashes
        if ($password === $row['password'] || password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['admin_id'];
            $_SESSION['admin_name'] = $row['full_name'];

            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = "The administrative security token or password provided is invalid.";
        }
    } else {
        $error = "Identified profile records matching that username do not exist.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrative Core Access | Let's Get Placed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #0f172a; /* Premium Slate Palette match */
            background-image: radial-gradient(circle at 0% 0%, #1e293b 0%, #0f172a 70%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Inter', sans-serif;
            padding: 20px;
        }
        /* Sleek Modern Login Card */
        .login-card {
            width: 440px;
            background: #ffffff;
            padding: 45px 40px;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .portal-header {
            text-align: center;
            margin-bottom: 32px;
        }
        .portal-logo {
            font-size: 1.6rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.75px;
            margin-bottom: 4px;
        }
        .portal-tagline {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        /* Modern Form Inputs styling */
        .form-label-custom {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
            margin-bottom: 6px;
        }
        .form-control-custom {
            border: 1px solid #e2e8f0;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 0.95rem;
            color: #0f172a;
            transition: all 0.2s ease;
        }
        .form-control-custom:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }
        /* Custom Button */
        .btn-submit-custom {
            background: #0f172a;
            color: #ffffff;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
            border: none;
            transition: background 0.2s ease;
        }
        .btn-submit-custom:hover {
            background: #1e293b;
            color: #ffffff;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Brand & Context Metadata -->
        <div class="portal-header">
            <div class="portal-logo">🎓 Let's Get Placed</div>
            <div class="portal-tagline">Administrative Control Core</div>
        </div>

        <!-- Exception Error Alert Module -->
        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger border-0 p-3 small mb-4" style="background-color: #fef2f2; color: #991b1b; border-radius: 8px;">
                ⚠️ <?php echo $error; ?>
            </div>
        <?php } ?>

        <!-- Administrative Verification Entry Form -->
        <form method="POST" autocomplete="off">
            <div class="mb-3">
                <label class="form-label-custom">Admin Username</label>
                <input type="text" 
                       name="username" 
                       class="form-control form-control-custom w-100" 
                       placeholder="e.g., placement_officer"
                       required>
            </div>

            <div class="mb-4">
                <label class="form-label-custom">Security Token / Password</label>
                <input type="password" 
                       name="password" 
                       class="form-control form-control-custom w-100" 
                       placeholder="••••••••••••"
                       required>
            </div>

            <button type="submit" name="login" class="btn btn-submit-custom w-100 shadow-sm mb-3">
                Verify Credentials Control →
            </button>
            
            <div class="text-center">
                <a href="index.php" class="text-decoration-none small text-muted fw-medium">
                    ← Return to Institutional Home Page
                </a>
            </div>
        </form>
    </div>

</body>
</html>
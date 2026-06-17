<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Let's Get Placed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
            color: #334155;
            overflow-x: hidden;
        }

        /* Sleek Fixed Glass Header */
        .custom-navbar {
            background: rgba(15, 23, 42, 0.95) !important;
            backdrop-filter: blur(8px);
            border-bottom: 1px solid #1e293b;
            padding: 18px 0;
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.5px;
            font-size: 1.3rem;
        }

        /* Premium Hero Section */
        .hero-section {
            background: radial-gradient(circle at top right, #1e293b 0%, #0f172a 100%);
            color: white;
            padding: 160px 0 120px 0;
            position: relative;
        }
        .hero-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(to top right, #f8f9fa 50%, transparent 50%);
        }
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            letter-spacing: -1.5px;
            line-height: 1.15;
            background: linear-gradient(180deg, #fff 0%, #cbd5e1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-subtitle {
            font-size: 1.25rem;
            color: #94a3b8;
            font-weight: 400;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Pill Badge Accents */
        .accent-badge {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            color: #60a5fa;
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        /* Feature Card Grid Block */
        .feature-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 40px 30px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            top: 0;
        }
        .feature-card:hover {
            top: -8px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.06);
            border-color: #cbd5e1;
        }
        .feature-icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 24px;
        }
        .bg-icon-1 { background: #eff6ff; color: #2563eb; }
        .bg-icon-2 { background: #f0fdf4; color: #16a34a; }
        .bg-icon-3 { background: #faf5ff; color: #7c3aed; }

        .feature-card h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
        }
        .feature-card p {
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0;
        }

        /* Button Modernizations */
        .btn-modern-primary {
            background: #2563eb;
            color: white;
            font-weight: 500;
            padding: 10px 24px;
            border-radius: 8px;
            border: none;
            transition: background 0.2s ease;
        }
        .btn-modern-primary:hover {
            background: #1d4ed8;
            color: white;
        }
        .btn-modern-outline {
            background: transparent;
            color: #94a3b8;
            font-weight: 500;
            padding: 10px 24px;
            border-radius: 8px;
            border: 1px solid #334155;
            transition: all 0.2s ease;
        }
        .btn-modern-outline:hover {
            border-color: #cbd5e1;
            color: #fff;
            background: rgba(255, 255, 255, 0.02);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark custom-navbar fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <span class="me-2" style="font-size: 1.4rem;">🎓</span> Let's Get Placed
            </a>
            
            <div class="ms-auto d-flex gap-2">
                <a href="login.php" class="btn btn-modern-outline btn-sm px-3">
                    Admin Portal
                </a>
                <a href="student_login.php" class="btn btn-modern-primary btn-sm px-4 shadow-sm">
                    Student Login →
                </a>
            </div>
        </div>
    </nav>

    <section class="hero-section text-center">
        <div class="container position-relative" style="z-index: 2;">
            <div class="mb-3">
                <span class="accent-badge text-uppercase">Next-Gen Placement Management System</span>
            </div>
            <h1 class="hero-title mb-3">Empowering Careers,<br>Simplifying Placements.</h1>
            <p class="hero-subtitle mb-0">
                A unified, end-to-end management infrastructure crafted for progressive institutions, leading recruiters, and ambitious students.
            </p>
        </div>
    </section>

    <div class="container" style="margin-top: -30px; position: relative; z-index: 10; margin-bottom: 80px;">
        <div class="row g-4 justify-content-center">
            
            <div class="col-md-4">
                <div class="card feature-card">
                    <div class="feature-icon-wrapper bg-icon-1">
                        👨‍🎓
                    </div>
                    <h3>For Students</h3>
                    <p>
                        Build beautiful digital dossiers, verify academic credentials instantly, track ongoing recruitment tracks, and apply directly to matching live openings.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card feature-card">
                    <div class="feature-icon-wrapper bg-icon-2">
                        🏢
                    </div>
                    <h3>For Recruiter Drives</h3>
                    <p>
                        Examine active institutional calendar frameworks, view rigorous package profiles, download functional job descriptions, and view minimum criteria matrices.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card feature-card">
                    <div class="feature-icon-wrapper bg-icon-3">
                        ⚡
                    </div>
                    <h3>For Placement Cells</h3>
                    <p>
                        Track student analytics, handle data exceptions seamlessly via internal support requests, broadcast notifications instantly, and generate executive exports.
                    </p>
                </div>
            </div>

        </div>
    </div>

    <footer class="text-center py-4 border-top bg-white">
        <div class="container">
            <p class="text-muted small m-0">&copy; 2026 Campus Placement Management System. Secure Administrative Core.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
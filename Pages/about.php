<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Us | FitZone Fitness Center</title>
  <style>
    /* Reset & Base */
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Segoe UI',sans-serif; background:#f4f4f4; color:#333; line-height:1.6; }

    /* Accent Colors */
    :root { --primary: #ff5722; --light: #fff; --dark: #222; }

    /* Navbar */
    .navbar {
      background: var(--primary);
      padding: 1rem 2rem;
      display:flex; justify-content:space-between; align-items:center;
    }
    .navbar .logo { font-size:1.5rem; color:var(--light); font-weight:bold; }
    .navbar nav a {
      color: var(--light);
      margin-left:1rem;
      text-decoration:none;
      font-size:.95rem;
    }
    .navbar nav a:hover { text-decoration:underline; }

    /* Page Header */
    .page-header {
      background: var(--primary) url('assets/about-hero.jpg') center/cover no-repeat;
      height: 250px; position: relative; display:flex;
      align-items:center; justify-content:center; text-align:center;
    }
    .page-header::after {
      content:''; position:absolute; inset:0; background:rgba(0,0,0,0.4);
    }
    .page-header h1 {
      position: relative; color:var(--light);
      font-size:2.5rem; z-index:1;
    }

    /* Main Content */
    .content {
      max-width:1000px; margin:2rem auto; padding:0 1rem;
    }

    /* Section Titles */
    .content h2 {
      color: var(--primary);
      margin-bottom:1rem; text-align:center;
      font-size:2rem;
    }

    /* Grid for Vision/Mission/History */
    .grid-3 {
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
      gap:2rem;
      margin-bottom:3rem;
    }
    .card {
      background:#fff;
      padding:1.5rem;
      border-radius:8px;
      box-shadow:0 2px 6px rgba(0,0,0,0.1);
    }
    .card h3 { margin-bottom:.75rem; color:var(--dark); }
    .card p { font-size:1rem; color:#555; }

    /* Value Statements */
    .values {
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
      gap:2rem;
      margin-bottom:3rem;
    }
    .value {
      background:var(--light);
      padding:1rem;
      border-left:4px solid var(--primary);
    }
    .value h4 { margin-bottom:.5rem; color:var(--primary); }
    .value p { font-size:1rem; }

    /* Footer */
    .footer {
      background: var(--primary);
      color: var(--light);
      text-align:center;
      padding:2rem 1rem;
      margin-top:4rem;
    }
    .footer a { color: var(--light); text-decoration:none; }
    .footer a:hover { text-decoration:underline; }
  </style>
</head>
<body>

  <!-- Navbar -->
  <header class="navbar">
    <div class="logo">FitZone</div>
    <nav>
      <a href="index.php">Home</a>
      <a href="about.php">About Us</a>
      <a href="blog.php">Blog</a>
      <a href="programs.php">Programs</a>
      <a href="trainers.php">Trainers</a>
      <a href="view_membership.php">Membership</a>
      <a href="contact.php">Contact</a>
      <?php if(!isset($_SESSION["user_id"])): ?>
        <a href="login.php">Login</a>
      <?php else: ?>
        <?php if($_SESSION["user_role"]==='admin'): ?>
          <a href="admin_dashboard.php">Dashboard</a>
        <?php elseif($_SESSION["user_role"]==='gym_management_staff'): ?>
          <a href="staff_dashboard.php">Dashboard</a>
        <?php else: ?>
          <a href="customer_dashboard.php">Dashboard</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
      <?php endif; ?>
    </nav>
  </header>

  <!-- Page Header -->
  <section class="page-header">
    <h1>About FitZone</h1>
  </section>

  <!-- Main Content -->
  <div class="content">

    <!-- Vision, Mission, History -->
    <h2>Who We Are</h2>
    <div class="grid-3">
      <div class="card">
        <h3>Our Vision</h3>
        <p>To empower every individual in Kurunegala with the tools and support needed for lifelong wellness.</p>
      </div>
      <div class="card">
        <h3>Our Mission</h3>
        <p>Deliver top‑tier fitness programs, expert guidance, and a supportive community in a state‑of‑the‑art facility.</p>
      </div>
      <div class="card">
        <h3>Our History</h3>
        <p>Founded in 2025 by local fitness enthusiasts, FitZone has grown into the region’s leading wellness hub.</p>
      </div>
    </div>

    <!-- Core Values -->
    <h2>Our Core Values</h2>
    <div class="values">
      <div class="value">
        <h4>Community</h4>
        <p>Foster a supportive, inclusive environment for every member.</p>
      </div>
      <div class="value">
        <h4>Excellence</h4>
        <p>Maintain the highest standards in training, equipment, and service.</p>
      </div>
      <div class="value">
        <h4>Integrity</h4>
        <p>Operate with honesty, transparency, and respect at all times.</p>
      </div>
      <div class="value">
        <h4>Wellness</h4>
        <p>Promote holistic health—physical, mental, and emotional well-being.</p>
      </div>
    </div>

    <!-- Call to Action -->
    <div style="text-align:center; margin-bottom:3rem;">
      <button class="btn-primary" onclick="location.href='register.php'">
        Become a Member Today
      </button>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer">
    <p>© <?php echo date('Y'); ?> FitZone Fitness Center. All rights reserved.</p>
    <p><a href="privacy.php">Privacy Policy</a> | <a href="terms.php">Terms of Service</a></p>
  </footer>

</body>
</html>
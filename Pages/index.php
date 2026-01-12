<?php
session_start();
// Connect to database
$conn = new mysqli("localhost", "root", "", "fitzone1");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>FitZone Fitness Center</title>
  <style>
    /* Reset & Base */
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Segoe UI',sans-serif; background:#f4f4f4; color:#333; }

    /* Accent Color */
    :root { --primary: #ff5722; --dark: #222; --light: #fff; }

    /* Navbar */
    .navbar {
      background: var(--primary);
      padding: 1rem 2rem;
      display:flex; justify-content:space-between; align-items:center;
    }
    .navbar .logo { font-size:1.5rem; color:var(--light); font-weight:bold; }
    .navbar a {
      color: var(--light);
      margin-left:1rem;
      text-decoration:none;
      font-size:.95rem;
    }
    .navbar a:hover { text-decoration:underline; }
 /* Hero Section */
    .hero {
      position: relative;
      height: 80vh;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      overflow: hidden;
    }

    .hero::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-size: cover;
      background-position: center;
      animation: slideshow 25s infinite;
      z-index: -1;
    }

    @keyframes slideshow {
      0% { background-image: url("../Images/home_image.jpg"); }
      20% { background-image: url("../Images/home2_image.jpg"); }
      40% { background-image: url("../Images/Strength_Training.jpg"); }
      60% { background-image: url("../Images/group_classes.jpg"); }
      80% { background-image: url("../Images/cardio.jpg"); }
      100% { background-image: url("../Images/home_image.jpg"); }
    }

    .hero h1 {
      font-size: 36px;
      margin-bottom: 10px;
    }

    .hero p {
      font-size: 18px;
    }
    .hero h1 { font-size:3rem; margin-bottom:1rem; }
    .hero p { font-size:1.2rem; margin-bottom:2rem; }
    .btn-primary {
      background: var(--primary);
      color: var(--light);
      padding:.75rem 1.5rem;
      border:none;
      border-radius:5px;
      cursor:pointer;
      font-size:1rem;
    }
    .btn-primary:hover { background:#e64a19; }

    /* About Us & Services */
    section { padding:4rem 2rem; }
    .about, .services, .trainers { background:var(--light); }
    h2 {
      text-align:center;
      font-size:2rem;
      color:var(--primary);
      margin-bottom:1rem;
    }

    /* About Grid */
    .about .grid, .trainers .grid {
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
      gap:2rem;
      margin-top:2rem;
    }
    .about .card, .trainer-card {
      background:#f9f9f9;
      padding:1.5rem;
      border-radius:8px;
      box-shadow:0 2px 6px rgba(0,0,0,0.1);
      text-align:center;
    }
    .about .card h3 { margin-bottom:.5rem; }

    /* Services List */
    .services ul {
      list-style:none;
      max-width:600px;
      margin:1rem auto 0;
      padding:0;
    }
    .services li {
      margin:1rem 0;
      font-size:1.1rem;
    }
    .services li::before {
      content:'✔';
      color:var(--primary);
      margin-right:.5rem;
    }

    /* Trainers */
    .trainer-card img {
      width:100%; height:200px; object-fit:cover;
      border-radius:6px; margin-bottom:1rem;
    }
    .trainer-card h4 { margin-bottom:.5rem; }
    .trainer-card p { color:#555; }

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

  <!-- Hero -->
  <section class="hero">
    <div class="inner">
      <h1>Welcome to FitZone</h1>
      <p>Your journey to fitness starts here.</p>
      <button class="btn-primary" onclick="location.href='register.php'">Join Now</button>
    </div>
  </section>

  <!-- About Us -->
  <section class="about">
    <h2>About Us</h2>
    <div class="grid">
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
  </section>

  <!-- Services -->
  <section class="services">
    <h2>Our Services</h2>
    <ul>
      <li>Cardio &amp; HIIT Workouts</li>
      <li>Strength Training</li>
      <li>Yoga &amp; Meditation</li>
      <li>Personal Training</li>
      <li>Nutrition Counseling</li>
    </ul>
  </section>

 
  <!-- Footer -->
  <footer class="footer">
    <p>© <?php echo date('Y'); ?> FitZone Fitness Center. All rights reserved.</p>
    <p><a href="privacy.php">Privacy Policy</a> | <a href="terms.php">Terms of Service</a></p>
  </footer>

</body>
</html>
`
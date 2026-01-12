<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Trainers | FitZone Fitness Center</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #36454F;
      color: white;
    }

    .navbar {
      background-color: #ff5722;
      color: white;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .navbar .logo {
      font-weight: bold;
      font-size: 24px;
    }

    .navbar a {
      color: white;
      text-decoration: none;
      margin-left: 15px;
      font-size: 16px;
    }

    .navbar a:hover {
      text-decoration: underline;
    }

    .container {
      max-width: 1100px;
      margin: 50px auto;
      padding: 20px;
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #ff5722;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 30px;
    }

    .trainer-card {
      background: #fff;
      color: #333;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      padding: 20px;
      text-align: center;
    }

    .trainer-card img {
      width: 100%;
      height: 300px;
      object-fit: cover;
      border-radius: 6px;
      margin-bottom: 15px;
    }

    .trainer-card h3 {
      margin: 0;
      color: #ff5722;
    }

    .trainer-card p {
      margin-top: 5px;
      font-size: 14px;
      color: #444;
    }

    .footer {
      background-color: #ff5722;
      color: white;
      text-align: center;
      padding: 15px 0;
      margin-top: 250px;
    }
  </style>
</head>

<body>
	<!-- Navigation Bar -->
<div class="navbar">
  <div class="logo">FitZone</div>
  <div>
    <a href="index.php">Home</a>
    <a href="blog.php">Blogs</a>
    <a href="programs.php">Programs</a>
    <a href="trainers.php">Trainers</a>
    <a href="membership.php">Membership</a>
    <a href="contact.php">Contact</a>
    <a href="login.php">Login</a>
  </div>
</div>

<!-- Trainers Section -->
<div class="container">
  <h2>Meet Our Trainers</h2>
  <div class="grid">

    <div class="trainer-card">
      <img src="../Images/staff_images/afrar.jpg" alt="Afrar Muhammed">
      <h3>Afrar Muhammed</h3>
      <p><strong>Specialty:</strong> Cardio & Weight Loss</p>
      <p>Afrar has 3+ years of experience helping clients burn fat and boost stamina through high-intensity programs.</p>
    </div>

    <div class="trainer-card">
      <img src="../Images/staff_images/shazan.jpg" alt="Shazan Shifan">
      <h3>Shazan Shifan</h3>
      <p><strong>Specialty:</strong> Yoga & Meditation</p>
      <p>Shazan is a certified yoga instructor focused on flexibility, breathing techniques, and overall wellness.</p>
    </div>

    <div class="trainer-card">
      <img src="../Images/staff_images/mohamed.jpg" alt="Injas Mohamed">
      <h3>Injas Mohamed</h3>
      <p><strong>Specialty:</strong> Strength & Muscle Building</p>
      <p>Mohamed creates personalized weight training programs to help members build muscle and gain strength safely.</p>
    </div>

  </div>
</div>

<!-- Footer -->
<div class="footer">
  <p>&copy; <?php echo date("Y"); ?> FitZone Fitness Center. All rights reserved.</p>
</div>

</body>
</body>
</html>
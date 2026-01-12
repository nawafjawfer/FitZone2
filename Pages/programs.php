<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Programs | FitZone Fitness Center</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #36454F; /* Charcoal Black */
      color: #ffffff;
    }

    /* Navbar */
    .navbar {
      background-color: #ff5722; /* Vibrant Orange */
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

    /* Programs Section */
    .container {
      padding: 40px 20px;
      text-align: center;
    }

    .container h2 {
      font-size: 32px;
      margin-bottom: 30px;
      color: #ff5722;
    }

    .program {
      background-color: #2c3e50;
      border-radius: 8px;
      padding: 25px;
      margin-bottom: 25px;
      max-width: 700px;
      margin-left: auto;
      margin-right: auto;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .program h3 {
      color: #ff5722;
      font-size: 24px;
      margin-bottom: 10px;
    }

    .program p {
      font-size: 16px;
      color: #ecf0f1;
    }

    .program img {
      width: 100%;
      height: auto;
      border-radius: 5px;
      margin-bottom: 15px;
    }

    /* Footer */
    .footer {
      background-color: #ff5722;
      color: white;
      text-align: center;
      padding: 15px 0;
      margin-top: 40px;
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

  <!-- Programs Content -->
  <div class="container">
    <h2>Our Fitness Programs</h2>

    <div class="program">
      <img src="../Images/cardio.jpg" alt="Cardio & HIIT">
      <h3>🏃‍♂️ Cardio & HIIT</h3>
      <p>Boost your stamina and burn calories with high-energy interval workouts.</p>
    </div>

    <div class="program">
      <img src="../Images/Strength_Training.jpg" alt="Strength Training">
      <h3>🏋️ Strength Training</h3>
      <p>Develop muscle and build strength with weight training programs.</p>
    </div>

    <div class="program">
      <img src="../Images/Yoga.jpg" alt="Yoga & Flexibility">
      <h3>🧘 Yoga & Flexibility</h3>
      <p>Improve your balance, flexibility, and peace of mind with guided yoga sessions.</p>
    </div>

    <div class="program">
      <img src="../Images/group_classes.jpg" alt="Group Classes">
      <h3>👥 Group Classes</h3>
      <p>Join Zumba, dance fitness, spinning, and more in a fun group atmosphere.</p>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <p>&copy; <?php echo date("Y"); ?> FitZone Fitness Center. All rights reserved.</p>
  </div>
</body>
</html>
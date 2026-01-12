<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Membership | FitZone Fitness Center</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #36454F;
      color: #fff;
    }

    /* Navbar */
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

    /* Membership Section */
    .container {
      max-width: 1100px;
      margin: 40px auto;
      padding: 20px;
      text-align: center;
    }

    h2 {
      margin-bottom: 40px;
      color: #ff5722;
    }

    .plans {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
    }

    .plan {
      background-color: #fff;
      color: #333;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
      transition: transform 0.3s;
    }

    .plan:hover {
      transform: translateY(-5px);
    }

    .plan h3 {
      color: #ff5722;
      font-size: 22px;
      margin-bottom: 15px;
    }

    .plan p {
      color: #444;
      font-size: 14px;
      line-height: 1.6;
    }

    .plan strong {
      display: block;
      margin-top: 20px;
      font-size: 18px;
      color: #222;
    }

    /* Footer */
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

  <!-- Membership Content -->
  <div class="container">
    <h2>Membership Plans</h2>
    <div class="plans">
      <div class="plan">
        <h3>Basic Plan</h3>
        <p>
          ✔ Access to gym during daytime hours<br>
          ✔ Group classes (limited)<br>
          ✔ Use of locker rooms
        </p>
        <strong>Price: $20/month</strong>
      </div>

      <div class="plan">
        <h3>Premium Plan</h3>
        <p>
          ✔ 24/7 Gym Access<br>
          ✔ Unlimited group classes<br>
          ✔ 1 Free personal training session/month<br>
          ✔ Access to sauna and spa
        </p>
        <strong>Price: $45/month</strong>
      </div>

      <div class="plan">
        <h3>VIP Plan</h3>
        <p>
          ✔ All Premium benefits<br>
          ✔ Weekly personal training<br>
          ✔ Customized meal & workout plans<br>
          ✔ Priority booking for all classes
        </p>
        <strong>Price: $70/month</strong>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <p>&copy; <?php echo date("Y"); ?> FitZone Fitness Center. All rights reserved.</p>
  </div>

</body>
</html>
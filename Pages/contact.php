<?php
session_start();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = htmlspecialchars($_POST["name"]);
  $email = htmlspecialchars($_POST["email"]);
  $message = htmlspecialchars($_POST["message"]);

  // Display message for now (we will save to DB later)
  $success = "Thank you, $name. Your message has been received!";
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Contact | FitZone Fitness Center</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #36454F;
      color: #fff;
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
      font-size: 24px;
      font-weight: bold;
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
      max-width: 600px;
      background: #fff;
      color: #333;
      margin: 60px auto;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }

    .container h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #ff5722;
    }

    label {
      font-weight: bold;
      display: block;
      margin-top: 15px;
    }

    input, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 15px;
    }

    button {
      width: 100%;
      padding: 12px;
      margin-top: 20px;
      background-color: #ff5722;
      color: white;
      border: none;
      border-radius: 4px;
      font-size: 16px;
      cursor: pointer;
    }

    button:hover {
      background-color: #e64a19;
    }

    .success {
      background-color: #d4edda;
      color: #155724;
      padding: 12px;
      border: 1px solid #c3e6cb;
      border-radius: 4px;
      margin-bottom: 20px;
      text-align: center;
    }

    .footer {
      background-color: #ff5722;
      color: white;
      text-align: center;
      padding: 15px 0;
      margin-top: 60px;
    }
  </style>
</head>

<body>
	 <!-- Navigation -->
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

  <!-- Contact Form -->
  <div class="container">
    <h2>Contact Us</h2>
    <?php if (!empty($success)) echo "<div class='success'>$success</div>"; ?>
    <form method="POST" action="contact.php">
      <label for="name">Your Name:</label>
      <input type="text" name="name" required>

      <label for="email">Your Email:</label>
      <input type="email" name="email" required>

      <label for="message">Your Message:</label>
      <textarea name="message" rows="5" required></textarea>

      <button type="submit">Send Message</button>
    </form>
  </div>

  <!-- Footer -->
  <div class="footer">
    <p>&copy; <?php echo date("Y"); ?> FitZone Fitness Center. All rights reserved.</p>
  </div>

</body>
</html>
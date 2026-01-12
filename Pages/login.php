<?php
session_start();
$conn = new mysqli("localhost", "root", "", "fitzone1");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = trim($_POST["username"]);
  $password = $_POST["password"];

  $stmt = $conn->prepare("SELECT * FROM users1 WHERE Username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $user = $stmt->get_result()->fetch_assoc();

  if ($user && password_verify($password, $user["Password"])) {
    $_SESSION["user_id"] = $user["Admission_Number"];
    $_SESSION["user_name"] = $user["Full_Name"];
    $_SESSION["user_role"] = $user["Role"];

    switch ($user["Role"]) {
      case "admin": header("Location: admin_dashboard.php"); exit;
      case "gym_management_staff": header("Location: staff_dashboard.php"); exit;
      default: header("Location: customer_dashboard.php"); exit;
    }
  } else {
    $error = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | FitZone</title>
  <style>
	body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #36454F; /* Charcoal Black */
      color: #ffffff;
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

    .login-container {
      max-width: 400px;
      margin: 80px auto;
      background-color: #fff;
      color: #333;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    .login-container h2 {
      text-align: center;
      color: #ff5722;
      margin-bottom: 20px;
    }

    label {
      font-weight: bold;
      display: block;
      margin-top: 15px;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    button {
      width: 100%;
      padding: 10px;
      margin-top: 20px;
      background-color: #ff5722;
      color: white;
      font-size: 16px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button:hover {
      background-color: #e64a19;
    }

    .error {
      color: red;
      text-align: center;
      margin-top: 10px;
    }

    .footer {
      background-color: #ff5722;
      color: white;
      text-align: center;
      padding: 15px 0;
      margin-top: 220px;
    }

    .register-link {
      text-align: center;
      margin-top: 15px;
    }

    .register-link a {
      color: #ff5722;
      text-decoration: none;
    }

    .register-link a:hover {
      text-decoration: underline;
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

  <!-- Login Form -->
  <div class="login-container">
    <h2>Login to FitZone</h2>
    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
    <form method="POST">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" required>

      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>

      <button type="submit">Login</button>
    </form>
    <div class="register-link">
      <p>Don’t have an account? <a href="register.php">Register here</a></p>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <p>&copy; <?php echo date("Y"); ?> FitZone Fitness Center. All rights reserved.</p>
  </div>

</body>
</html>
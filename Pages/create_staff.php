<?php
session_start();
$conn = new mysqli("localhost", "root", "", "fitzone1");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Only admin allowed
if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "admin") {
  header("Location: login.php");
  exit();
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $fullname = htmlspecialchars($_POST["fullname"]);
  $nic = htmlspecialchars($_POST["nic"]);
  $email = htmlspecialchars($_POST["email"]);
  $address = htmlspecialchars($_POST["address"]);
  $phone = htmlspecialchars($_POST["phone"]);
  $username = htmlspecialchars($_POST["username"]);
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
  $role = "gym_management_staff";

  $check = $conn->prepare("SELECT * FROM users1 WHERE Email = ? OR Username = ?");
  $check->bind_param("ss", $email, $username);
  $check->execute();
  $result = $check->get_result();

  if ($result->num_rows > 0) {
    $error = "❌ Email or Username already exists.";
  } else {
    $sql = "INSERT INTO users1 (Full_Name, NIC, Email, Address, Phone_Number, Weight, Height, Username, Password, Role)
            VALUES (?, ?, ?, ?, ?, '0', '0', ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
      $stmt->bind_param("ssssssss", $fullname, $nic, $email, $address, $phone, $username, $password, $role);
      if ($stmt->execute()) {
        $success = "✅ Gym management staff created successfully.";
      } else {
        $error = "❌ Failed to execute insertion.";
      }
    } else {
      $error = "❌ SQL prepare failed.";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Staff | FitZone</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #36454F;
      margin: 0;
      padding: 0;
    }

    .navbar {
      background-color: #ff5722;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: white;
    }

    .navbar .logo {
      font-weight: bold;
      font-size: 24px;
    }

    .navbar a {
      color: white;
      margin-left: 15px;
      text-decoration: none;
      font-size: 16px;
    }

    .navbar a:hover {
      text-decoration: underline;
    }

    .container {
      max-width: 550px;
      margin: 60px auto;
      background-color: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    h2 {
      text-align: center;
      color: #ff5722;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }

    input, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #aaa;
      border-radius: 4px;
    }

    button {
      margin-top: 25px;
      width: 100%;
      padding: 12px;
      background-color: #ff5722;
      color: white;
      border: none;
      font-size: 16px;
      border-radius: 4px;
      cursor: pointer;
    }

    button:hover {
      background-color: #e64a19;
    }

    .message {
      text-align: center;
      margin-top: 15px;
      font-weight: bold;
    }

    .message.success {
      color: green;
    }

    .message.error {
      color: red;
    }

    .back-link {
      text-align: center;
      margin-top: 20px;
    }

    .back-link a {
      color: #ff5722;
      text-decoration: none;
    }

    .back-link a:hover {
      text-decoration: underline;
    }

    .footer {
      background-color: #ff5722;
      color: white;
      text-align: center;
      padding: 15px 0;
      margin-top: 50px;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <div class="navbar">
    <div class="logo">FitZone</div>
    <div>
      <a href="index.php">Home</a>
      <a href="blog.php">Blogs</a>
      <a href="programs.php">Programs</a>
      <a href="trainers.php">Trainers</a>
      <a href="membership.php">Membership</a>
      <a href="contact.php">Contact</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <!-- Form Section -->
  <div class="container">
    <h2>Create Gym Management Staff</h2>

    <?php
    if (!empty($success)) echo "<div class='message success'>$success</div>";
    if (!empty($error)) echo "<div class='message error'>$error</div>";
    ?>

    <form method="POST">
      <label>Full Name:</label>
      <input type="text" name="fullname" required>

      <label>NIC:</label>
      <input type="text" name="nic" required>

      <label>Email:</label>
      <input type="email" name="email" required>

      <label>Address:</label>
      <textarea name="address" rows="2" required></textarea>

      <label>Phone Number:</label>
      <input type="text" name="phone" required>

      <label>Username:</label>
      <input type="text" name="username" required>

      <label>Password:</label>
      <input type="password" name="password" required>

      <button type="submit">Create Staff</button>
    </form>

    <div class="back-link">
      <a href="admin_dashboard.php">← Back to Dashboard</a>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <p>&copy; <?php echo date("Y"); ?> FitZone Fitness Center. All rights reserved.</p>
  </div>

</body>
</html>
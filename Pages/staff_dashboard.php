<?php
session_start();
$conn = new mysqli("localhost", "root", "", "fitzone1");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "gym_management_staff") {
  header("Location: login.php");
  exit();
}
$user_id = $_SESSION["user_id"];
$sql = "SELECT * FROM users1 WHERE Admission_Number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$admission_no = "S" . str_pad($user["Admission_Number"], 3, "0", STR_PAD_LEFT);
$unanswered_query = $conn->query("SELECT COUNT(*) AS total FROM queries WHERE Response IS NULL OR Response = ''");
$count = $unanswered_query->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Staff Dashboard | FitZone</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: url("../Images/home_image.jpg") no-repeat center center fixed;
      background-size: cover;
      color: #333;
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
      max-width: 700px;
      margin: 60px auto;
      background: rgba(255,255,255,0.97);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    h2 {
      color: #ff5722;
      margin-bottom: 20px;
      text-align: center;
    }

    p {
      font-size: 16px;
      margin-bottom: 10px;
    }

    .alert {
      color: red;
      font-weight: bold;
      text-align: center;
      margin-bottom: 15px;
    }

    .dashboard-buttons {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      justify-content: center;
      margin-top: 20px;
    }

    .button {
      padding: 12px 20px;
      background-color: #ff5722;
      color: white;
      text-decoration: none;
      font-size: 15px;
      border-radius: 6px;
      transition: background 0.3s;
    }

    .button:hover {
      background-color: #e64a19;
    }

    .footer {
      background-color: #ff5722;
      color: white;
      text-align: center;
      padding: 15px 0;
      margin-top: 240px;
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
      <a href="contact.php">Contact</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($user["Full_Name"]); ?> (Staff)</h2>
    <p><strong>Admission Number:</strong> <?php echo $admission_no; ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user["Email"]); ?></p>
    <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($user["Phone_Number"]); ?></p>

    <?php if ($count > 0): ?>
      <p class="alert">🔔 You have <?php echo $count; ?> unanswered query(ies) from customers.</p>
    <?php endif; ?>

    <div class="dashboard-buttons">
      <a class="button" href="staff_appointments.php">📅 Manage Appointments</a>
      <a class="button" href="view_queries_staff.php">💬 Respond to Queries</a>
      <a class="button" href="edit_profile.php">✏️ Edit My Profile</a>
      <a class="button" href="logout.php">🚪 Logout</a>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <p>&copy; <?php echo date("Y"); ?> FitZone Fitness Center. All rights reserved.</p>
  </div>

</body>
</html>
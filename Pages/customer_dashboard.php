<?php
session_start();

// Connect to database
$conn = new mysqli("localhost", "root", "", "fitzone1");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Allow only customers
if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "customer") {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION["user_id"];

// Get user data
$sql = "SELECT * FROM users1 WHERE Admission_Number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$admission = "C" . str_pad($user["Admission_Number"], 3, "0", STR_PAD_LEFT);

// Membership expiry check
$warning = "";
$check_expiry = $conn->prepare("SELECT End_Date FROM memberships 
                                WHERE Admission_Number = ? 
                                ORDER BY End_Date DESC LIMIT 1");
$check_expiry->bind_param("i", $user_id);
$check_expiry->execute();
$res = $check_expiry->get_result();
if ($res->num_rows > 0) {
  $end_date = $res->fetch_assoc()['End_Date'];
  $days_left = floor((strtotime($end_date) - time()) / (60 * 60 * 24));
  if ($days_left <= 3 && $days_left >= 0) {
    $warning = "⚠️ Your membership is expiring in $days_left day(s). Please renew it.";
  } elseif ($days_left < 0) {
    $warning = "⚠️ Your membership has expired. Please purchase a new plan.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Dashboard | FitZone</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: url("../Images/home2_image.jpg") no-repeat center center fixed;
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

    .dashboard {
      max-width: 750px;
      margin: 60px auto;
      background: rgba(255,255,255,0.95);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    h2 {
      color: #ff5722;
      margin-bottom: 15px;
      text-align: center;
    }

    h3 {
      margin-top: 30px;
      color: #333;
    }

    p {
      font-size: 16px;
      margin: 8px 0;
    }

    .warning {
      background-color: #fff3cd;
      color: #856404;
      border: 1px solid #ffeeba;
      padding: 12px;
      border-radius: 6px;
      margin-bottom: 20px;
      font-weight: bold;
      text-align: center;
    }

    .buttons {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      margin-top: 20px;
      justify-content: center;
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
      <a href="programs.php">Programs</a>
      <a href="trainers.php">Trainers</a>
      <a href="contact.php">Contact</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <!-- Main Dashboard -->
  <div class="dashboard">
    <h2>Welcome, <?php echo htmlspecialchars($user['Full_Name']); ?>!</h2>
    <p><strong>Admission Number:</strong> <?php echo $admission; ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['Email']); ?></p>

    <?php if (!empty($warning)): ?>
      <div class="warning"><?php echo $warning; ?></div>
    <?php endif; ?>

    <h3>Quick Access</h3>
    <div class="buttons">
      <a class="button" href="update_profile.php">👤 View & Update Profile</a>
      <a class="button" href="book_appointment.php">📅 Book Appointment</a>
      <a class="button" href="view_appointments.php">🗓️ View & Modify Appointments</a>
      <a class="button" href="view_membership.php">💳 Membership Details</a>
      <a class="button" href="submit_query.php">📨 Submit & 📋 View Queries</a>
      <a class="button" href="logout.php">🚪 Logout</a>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <p>&copy; <?php echo date("Y"); ?> FitZone Fitness Center. All rights reserved.</p>
  </div>

</body>
</html>
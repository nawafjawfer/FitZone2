<?php
session_start();

// Connect to database
$conn = new mysqli("localhost", "root", "", "fitzone1");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Only allow access if role is admin
if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] != "admin") {
  header("Location: login.php");
  exit();
}

// Count unanswered queries
$unanswered_query = $conn->query("SELECT COUNT(*) AS total FROM queries WHERE Response IS NULL OR Response = ''");
$count = $unanswered_query->fetch_assoc()['total'];
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Dashboard | FitZone</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background: url("../Images/home_image.jpg") no-repeat center center fixed;
      background-size: cover;
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
      max-width: 800px;
      margin: 60px auto;
      background: rgba(255, 255, 255, 0.97);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    h2 {
      color: #ff5722;
      text-align: center;
      margin-bottom: 20px;
    }

    p {
      text-align: center;
      font-size: 16px;
      margin-bottom: 25px;
    }

    .alert {
      text-align: center;
      color: red;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .dashboard-buttons {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 15px;
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
      margin-top: 290px;
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

  <!-- Dashboard Content -->
  <div class="container">
    <h2>Welcome, <?php echo $_SESSION["user_name"]; ?> (Admin)</h2>
    <p>You are logged in as <strong>Admin</strong>.</p>

    <?php if ($count > 0): ?>
      <div class="alert">🔔 You have <?php echo $count; ?> unanswered query(ies)</div>
    <?php endif; ?>

    <div class="dashboard-buttons">
      <a href="edit_profile.php" class="button">✏️ Edit My Profile</a>
      <a href="create_staff.php" class="button">➕ Create Gym Staff</a>
      <a href="create_admin.php" class="button">➕ Add Another Admin</a>
      <a href="view_users.php" class="button">👥 View Users & Roles</a>
      <a href="view_queries_admin.php" class="button">📨 Respond to Queries</a>
      <a href="create_post.php" class="button">📝 Create Blog Post</a>
      <a href="manage_posts.php" class="button">🗂️ Manage Blog Posts</a>
      <a href="logout.php" class="button">🚪 Logout</a>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <p>&copy; <?php echo date("Y"); ?> FitZone Fitness Center. All rights reserved.</p>
  </div>

</body>
</html>
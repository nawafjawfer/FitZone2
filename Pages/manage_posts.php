<?php
session_start();
$conn = new mysqli("localhost", "root", "", "fitzone1");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "admin") {
  header("Location: login.php");
  exit();
}

// Delete post
if (isset($_GET["delete"])) {
  $post_id = intval($_GET["delete"]);

  $img = $conn->query("SELECT Image FROM blog_posts WHERE Post_ID = $post_id")->fetch_assoc();
  if ($img && !empty($img["Image"])) {
    $image_path = "uploads/" . $img["Image"];
    if (file_exists($image_path)) {
      unlink($image_path);
    }
  }

  $conn->query("DELETE FROM blog_posts WHERE Post_ID = $post_id");
  header("Location: manage_posts.php?deleted=1");
  exit();
}

// Get posts
$result = $conn->query("SELECT * FROM blog_posts ORDER BY Created_At DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Blog Posts | Admin</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #36454F;
      margin: 0;
      padding: 0;
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
      max-width: 1000px;
      margin: 50px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #ff5722;
    }

    .success {
      text-align: center;
      color: green;
      margin-bottom: 15px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 12px;
      text-align: center;
      vertical-align: middle;
    }

    th {
      background-color: #ff5722;
      color: white;
    }

    a.btn {
      padding: 6px 14px;
      border-radius: 4px;
      text-decoration: none;
      margin: 2px;
      color: white;
      font-weight: bold;
    }

    .edit-btn {
      background-color: #28a745;
    }

    .edit-btn:hover {
      background-color: #218838;
    }

    .delete-btn {
      background-color: #dc3545;
    }

    .delete-btn:hover {
      background-color: #c82333;
    }

    .back {
      text-align: center;
      margin-top: 25px;
    }

    .back a {
      text-decoration: none;
      color: #ff5722;
      font-weight: bold;
    }

    .back a:hover {
      text-decoration: underline;
    }

    .footer {
      background-color: #ff5722;
      color: white;
      text-align: center;
      padding: 15px 0;
      margin-top: 300px;
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
    <a href="membership.php">Membership</a>
    <a href="contact.php">Contact</a>
    <a href="logout.php">Logout</a>
  </div>
</div>

<!-- Main -->
<div class="container">
  <h2>Manage Blog Posts</h2>

  <?php if (isset($_GET["deleted"])): ?>
    <div class="success">✅ Blog post deleted successfully!</div>
  <?php endif; ?>

  <table>
    <tr>
      <th>Title</th>
      <th>Category</th>
      <th>Date</th>
      <th>Author</th>
      <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($row["Title"]); ?></td>
        <td><?php echo htmlspecialchars($row["Category"]); ?></td>
        <td><?php echo date("Y-m-d", strtotime($row["Created_At"])); ?></td>
        <td><?php echo htmlspecialchars($row["Author"]); ?></td>
        <td>
          <a class="btn edit-btn" href="edit_post.php?id=<?php echo $row["Post_ID"]; ?>">Edit</a>
          <a class="btn delete-btn" href="manage_posts.php?delete=<?php echo $row["Post_ID"]; ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <div class="back">
    <a href="admin_dashboard.php">← Back to Admin Dashboard</a>
  </div>
</div>

<!-- Footer -->
<div class="footer">
  <p>&copy; <?php echo date("Y"); ?> FitZone Fitness Center. All rights reserved.</p>
</div>

</body>
</html>
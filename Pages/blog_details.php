<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "fitzone1");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


// Check if blog post ID is passed
if (!isset($_GET["id"])) {
  header("Location: blog.php");
  exit();
}

$post_id = intval($_GET["id"]);
$post = $conn->query("SELECT * FROM blog_posts WHERE Post_ID = $post_id")->fetch_assoc();

if (!$post) {
  die("Blog post not found.");
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
 <title><?php echo htmlspecialchars($post['Title']); ?> | FitZone Blog</title>
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
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .navbar .logo {
      font-weight: bold;
      font-size: 24px;
      color: white;
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
      max-width: 900px;
      background: white;
      margin: 40px auto;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
      color: #333;
    }

    h1 {
      color: #ff5722;
      margin-bottom: 10px;
    }

    .meta {
      font-size: 14px;
      color: #777;
      margin-bottom: 20px;
    }

    img {
      width: 100%;
      max-height: 400px;
      object-fit: cover;
      border-radius: 6px;
      margin-bottom: 20px;
    }

    .content {
      font-size: 17px;
      line-height: 1.7;
    }

    .back {
      text-align: center;
      margin-top: 30px;
    }

    .back a {
      color: #ff5722;
      text-decoration: none;
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
      margin-top: 40px;
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
    <a href="login.php">Login</a>
  </div>
</div>

<!-- Blog Post Container -->
<div class="container">
  <h1><?php echo htmlspecialchars($post['Title']); ?></h1>

  <div class="meta">
    🏷️ <?php echo htmlspecialchars($post['Category']); ?> |
    ✍️ <?php echo htmlspecialchars($post['Author']); ?> |
    📅 <?php echo date("F j, Y", strtotime($post['Created_At'])); ?>
  </div>

  <?php if (!empty($post['Image'])): ?>
    <img src="uploads/<?php echo htmlspecialchars($post['Image']); ?>" alt="Blog Image">
  <?php endif; ?>

  <div class="content">
    <?php echo nl2br(htmlspecialchars($post['Content'])); ?>
  </div>

  <div class="back">
    <a href="blog.php">← Back to Blog</a>
  </div>
</div>

<!-- Footer -->
<div class="footer">
  <p>&copy; <?php echo date("Y"); ?> FitZone Fitness Center. All rights reserved.</p>
</div>
</body>
</html>
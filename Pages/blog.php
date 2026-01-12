<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "fitzone1");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch blog posts
$posts = $conn->query("SELECT * FROM blog_posts ORDER BY Created_At DESC");
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>FitZone Blog</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #36454F; /* Charcoal Black */
      color: white;
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
      max-width: 1000px;
      margin: 30px auto;
      background: #fff;
      color: #333;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }

    h1 {
      text-align: center;
      color: #ff5722;
      margin-bottom: 30px;
    }

    .post {
      border-bottom: 1px solid #ccc;
      padding: 20px 0;
    }

    .post h2 {
      margin: 0;
      color: #ff5722;
    }

    .meta {
      font-size: 14px;
      color: #666;
      margin-top: 5px;
    }

    .post img {
      max-width: 100%;
      height: auto;
      border-radius: 6px;
      margin-top: 10px;
    }

    .excerpt {
      margin: 15px 0;
      font-size: 16px;
      color: #444;
    }

    .read-more {
      display: inline-block;
      background-color: #ff5722;
      color: white;
      padding: 10px 16px;
      text-decoration: none;
      border-radius: 4px;
      font-weight: bold;
    }

    .read-more:hover {
      background-color: #e64a19;
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

<!-- Blog Container -->
<div class="container">
  <h1>FitZone Blog</h1>

  <?php while ($row = $posts->fetch_assoc()): ?>
    <div class="post">
      <h2><?php echo htmlspecialchars($row['Title']); ?></h2>
      <div class="meta">
        🏷️ <?php echo htmlspecialchars($row['Category']); ?> |
        ✍️ <?php echo htmlspecialchars($row['Author']); ?> |
        📅 <?php echo date("F j, Y", strtotime($row['Created_At'])); ?>
      </div>

      <?php if (!empty($row['Image'])): ?>
        <img src="uploads/<?php echo htmlspecialchars($row['Image']); ?>" alt="Blog Image">
      <?php endif; ?>

      <div class="excerpt">
        <?php
          $preview = substr(strip_tags($row['Content']), 0, 200);
          echo nl2br(htmlspecialchars($preview)) . "...";
        ?>
      </div>

      <a class="read-more" href="blog_details.php?id=<?php echo $row['Post_ID']; ?>">Read More</a>
    </div>
  <?php endwhile; ?>

  <div class="back">
    <a href="index.php">← Back to Home</a>
  </div>
</div>

<!-- Footer -->
<div class="footer">
  <p>&copy; <?php echo date("Y"); ?> FitZone Fitness Center. All rights reserved.</p>
</div>
</body>
</html>
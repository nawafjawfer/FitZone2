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

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $title = trim($_POST["title"]);
  $content = trim($_POST["content"]);
  $author = $_SESSION["user_name"];
  $category = $_POST["category"];
  $image_name = "";

  if (!empty($_FILES["image"]["name"])) {
    $target_dir = "uploads/";
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;

    if (!is_dir($target_dir)) {
      mkdir($target_dir);
    }

    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
      $error = "❌ Failed to upload image.";
    }
  }

  if (empty($error)) {
    $stmt = $conn->prepare("INSERT INTO blog_posts (Title, Content, Author, Category, Image) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $content, $author, $category, $image_name);
    if ($stmt->execute()) {
      $success = "✅ Blog post created successfully!";
    } else {
      $error = "❌ Failed to save blog post.";
    }
  }
}
$conn->set_charset("utf8mb4");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Blog Post | FitZone Admin</title>
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
      max-width: 700px;
      margin: 50px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
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

    input[type="text"],
    textarea,
    select {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    input[type="file"] {
      margin-top: 8px;
    }

    button {
      background-color: #ff5722;
      color: white;
      padding: 12px;
      margin-top: 20px;
      border: none;
      border-radius: 5px;
      width: 100%;
      font-size: 16px;
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

    .success { color: green; }
    .error { color: red; }

    .back {
      text-align: center;
      margin-top: 20px;
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
    <a href="membership.php">Membership</a>
    <a href="contact.php">Contact</a>
    <a href="logout.php">Logout</a>
  </div>
</div>

<!-- Form Container -->
<div class="container">
  <h2>Create Blog Post</h2>

  <?php if ($success): ?>
    <p class="message success"><?php echo $success; ?></p>
  <?php elseif ($error): ?>
    <p class="message error"><?php echo $error; ?></p>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <label>Title</label>
    <input type="text" name="title" required>

    <label>Category</label>
    <select name="category" required>
      <option value="">--Select Category--</option>
      <option value="Workout Routine">Workout Routine</option>
      <option value="Healthy Recipe">Healthy Recipe</option>
      <option value="Meal Plan">Meal Plan</option>
      <option value="Success Story">Success Story</option>
    </select>

    <label>Content</label>
    <textarea name="content" rows="8" required></textarea>

    <label>Upload Image</label>
    <input type="file" name="image" accept="image/*">

    <button type="submit">Post Blog</button>
  </form>

  <div class="back">
    <a href="admin_dashboard.php">← Back to Dashboard</a>
  </div>
</div>

<!-- Footer -->
<div class="footer">
  <p>&copy; <?php echo date("Y"); ?> FitZone Fitness Center. All rights reserved.</p>
</div>

</body>
</html>

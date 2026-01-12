<?php
session_start();
// Connect to database
$conn = new mysqli("localhost", "root", "", "fitzone1");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Ensure only admin can access
if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "admin") {
  header("Location: login.php");
  exit();
}

// Check if post ID is provided
if (!isset($_GET["id"])) {
  header("Location: manage_posts.php");
  exit();
}

$post_id = intval($_GET["id"]);
$success = "";
$error = "";

// Fetch existing post data
$post = $conn->query("SELECT * FROM blog_posts WHERE Post_ID = $post_id")->fetch_assoc();
if (!$post) {
  die("Post not found.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $title = trim($_POST["title"]);
  $content = trim($_POST["content"]);
  $category = $_POST["category"];

  // Check if a new image is uploaded
  if (!empty($_FILES["image"]["name"])) {
    $target_dir = "uploads/";
    $new_image = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $new_image;

    // Upload new image
    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
      $error = "❌ Failed to upload new image.";
    } else {
      // Delete old image
      if (!empty($post["Image"])) {
        $old_path = $target_dir . $post["Image"];
        if (file_exists($old_path)) {
          unlink($old_path);
        }
      }

      // Update with new image
      $stmt = $conn->prepare("UPDATE blog_posts SET Title=?, Content=?, Category=?, Image=? WHERE Post_ID=?");
      $stmt->bind_param("ssssi", $title, $content, $category, $new_image, $post_id);
    }
  } else {
    // Update without changing image
    $stmt = $conn->prepare("UPDATE blog_posts SET Title=?, Content=?, Category=? WHERE Post_ID=?");
    $stmt->bind_param("sssi", $title, $content, $category, $post_id);
  }

  if (empty($error) && $stmt->execute()) {
    $success = "✅ Blog post updated successfully!";
    // Refresh post info
    $post = $conn->query("SELECT * FROM blog_posts WHERE Post_ID = $post_id")->fetch_assoc();
  } else if (empty($error)) {
    $error = "❌ Failed to update blog post.";
  }
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Blog Post | Admin</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f8f8;
      padding: 40px;
    }

    .container {
      max-width: 700px;
      background: white;
      margin: auto;
      padding: 30px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    h2 {
      text-align: center;
      color: #007bff;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }

    input[type="text"], textarea, select {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    .current-img {
      margin-top: 10px;
    }

    button {
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
      margin-top: 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
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
      color: #007bff;
    }

    .back a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
	
	<div class="container">
  <h2>Edit Blog Post</h2>

  <?php if ($success): ?>
    <p class="message success"><?php echo $success; ?></p>
  <?php elseif ($error): ?>
    <p class="message error"><?php echo $error; ?></p>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <label>Title</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($post["Title"]); ?>" required>

    <label>Category</label>
    <select name="category" required>
      <option value="">--Select Category--</option>
      <option value="Workout Routine" <?php if($post["Category"] == "Workout Routine") echo "selected"; ?>>Workout Routine</option>
      <option value="Healthy Recipe" <?php if($post["Category"] == "Healthy Recipe") echo "selected"; ?>>Healthy Recipe</option>
      <option value="Meal Plan" <?php if($post["Category"] == "Meal Plan") echo "selected"; ?>>Meal Plan</option>
      <option value="Success Story" <?php if($post["Category"] == "Success Story") echo "selected"; ?>>Success Story</option>
    </select>

    <label>Content</label>
    <textarea name="content" rows="8" required><?php echo htmlspecialchars($post["Content"]); ?></textarea>

    <label>Current Image</label>
    <?php if (!empty($post["Image"])): ?>
      <div class="current-img">
        <img src="uploads/<?php echo $post["Image"]; ?>" alt="Current Image" width="200">
      </div>
    <?php else: ?>
      <p>No image uploaded.</p>
    <?php endif; ?>

    <label>Replace Image (optional)</label>
    <input type="file" name="image" accept="image/*">

    <button type="submit">Update Post</button>
  </form>

  <div class="back">
    <a href="manage_posts.php">← Back to Blog Management</a>
  </div>
</div>
</body>
</html>
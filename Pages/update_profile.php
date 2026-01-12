<?php
session_start();

// Connect to database
$conn = new mysqli("localhost", "root", "", "fitzone1");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Allow only logged-in customers
if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "customer") {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION["user_id"];

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users1 WHERE Admission_Number = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$success = "";
$error = "";

// Handle update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $fullname = htmlspecialchars($_POST["fullname"]);
  $nic = htmlspecialchars($_POST["nic"]);
  $email = htmlspecialchars($_POST["email"]);
  $address = htmlspecialchars($_POST["address"]);
  $phone = htmlspecialchars($_POST["phone"]);
  $weight = htmlspecialchars($_POST["weight"]);
  $height = htmlspecialchars($_POST["height"]);

  $update = $conn->prepare("UPDATE users1 SET Full_Name=?, NIC=?, Email=?, Address=?, Phone_Number=?, Weight=?, Height=? WHERE Admission_Number=?");
  $update->bind_param("sssssssi", $fullname, $nic, $email, $address, $phone, $weight, $height, $user_id);

  if ($update->execute()) {
    $success = "✅ Profile updated successfully!";
    // Refresh user data
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
  } else {
    $error = "❌ Failed to update profile.";
  }

  $update->close();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Update Profile | FitZone</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #36454F;
      background-size: cover;
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
      max-width: 500px;
      margin: 60px auto;
      background: rgba(255,255,255,0.97);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.3);
    }

    h2 {
      text-align: center;
      color: #ff5722;
      margin-bottom: 20px;
    }

    label {
      font-weight: bold;
      margin-top: 15px;
      display: block;
    }

    input, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 14px;
    }

    button {
      width: 100%;
      padding: 12px;
      margin-top: 20px;
      background-color: #ff5722;
      border: none;
      border-radius: 5px;
      color: white;
      font-size: 16px;
      cursor: pointer;
    }

    button:hover {
      background-color: #e64a19;
    }

    .message {
      text-align: center;
      font-weight: bold;
      margin-top: 15px;
    }

    .success { color: green; }
    .error { color: red; }

    .back {
      text-align: center;
      margin-top: 15px;
    }

    .back a {
      color: #ff5722;
      text-decoration: none;
    }

    .back a:hover {
      text-decoration: underline;
    }

    .footer {
      background-color: #ff5722;
      color: white;
      text-align: center;
      padding: 15px 0;
      margin-top: 60px;
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

  <!-- Main Container -->
  <div class="container">
    <h2>Update Your Profile</h2>

    <?php
      if (!empty($success)) echo "<div class='message success'>$success</div>";
      if (!empty($error)) echo "<div class='message error'>$error</div>";
    ?>

    <form method="POST">
      <label>Full Name:</label>
      <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['Full_Name']); ?>" required>

      <label>NIC:</label>
      <input type="text" name="nic" value="<?php echo htmlspecialchars($user['NIC']); ?>" required>

      <label>Email:</label>
      <input type="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required>

      <label>Address:</label>
      <textarea name="address" rows="2" required><?php echo htmlspecialchars($user['Address']); ?></textarea>

      <label>Phone Number:</label>
      <input type="text" name="phone" value="<?php echo htmlspecialchars($user['Phone_Number']); ?>" required>

      <label>Weight (kg):</label>
      <input type="text" name="weight" value="<?php echo htmlspecialchars($user['Weight']); ?>">

      <label>Height (cm):</label>
      <input type="text" name="height" value="<?php echo htmlspecialchars($user['Height']); ?>">

      <button type="submit">Update Profile</button>
    </form>

    <div class="back">
      <a href="customer_dashboard.php">← Back to Dashboard</a>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <p>&copy; <?php echo date("Y"); ?> FitZone Fitness Center. All rights reserved.</p>
  </div>

</body>
</html>
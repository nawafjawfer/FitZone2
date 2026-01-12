<?php
session_start();
$conn = new mysqli("localhost", "root", "", "fitzone1");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION["user_id"]) || !in_array($_SESSION["user_role"], ["admin", "gym_management_staff"])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION["user_id"];
$role = $_SESSION["user_role"];
$success = "";
$error = "";

$stmt = $conn->prepare("SELECT * FROM users1 WHERE Admission_Number = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $fullname = htmlspecialchars($_POST["fullname"]);
  $nic = htmlspecialchars($_POST["nic"]);
  $email = htmlspecialchars($_POST["email"]);
  $address = htmlspecialchars($_POST["address"]);
  $phone = htmlspecialchars($_POST["phone"]);

  $update = $conn->prepare("UPDATE users1 SET Full_Name=?, NIC=?, Email=?, Address=?, Phone_Number=? WHERE Admission_Number=?");
  $update->bind_param("sssssi", $fullname, $nic, $email, $address, $phone, $user_id);

  if ($update->execute()) {
    $success = "✅ Profile updated successfully!";
    $user = $conn->query("SELECT * FROM users1 WHERE Admission_Number = $user_id")->fetch_assoc();
  } else {
    $error = "❌ Failed to update profile.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Profile | FitZone</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #36454F; /* Charcoal black */
      margin: 0;
      padding: 0;
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
      font-size: 22px;
      font-weight: bold;
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
      max-width: 600px;
      margin: 60px auto;
      background: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
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
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    button {
      margin-top: 25px;
      width: 100%;
      padding: 12px;
      background-color: #ff5722;
      color: white;
      font-size: 16px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button:hover {
      background-color: #e64a19;
    }

    .message {
      text-align: center;
      margin-top: 10px;
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
      font-weight: bold;
    }

    .back-link a:hover {
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
  <!-- Navigation -->
  <div class="navbar">
    <div class="logo">FitZone</div>
    <div>
      <a href="index.php">Home</a>
      <a href="programs.php">Programs</a>
      <a href="trainers.php">Trainers</a>
      <a href="membership.php">Membership</a>
      <a href="contact.php">Contact</a>
    </div>
  </div>

  <!-- Form Container -->
  <div class="container">
    <h2>Edit My Profile</h2>

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

      <button type="submit">Update Profile</button>
    </form>

    <div class="back-link">
      <a href="<?php echo $role === 'admin' ? 'admin_dashboard.php' : 'staff_dashboard.php'; ?>">← Back to Dashboard</a>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <p>&copy; <?php echo date("Y"); ?> FitZone Fitness Center. All rights reserved.</p>
  </div>
</body>
</html>
<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "fitzone1");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $fullname = htmlspecialchars($_POST["fullname"]);
  $nic = htmlspecialchars($_POST["nic"]);
  $email = htmlspecialchars($_POST["email"]);
  $address = htmlspecialchars($_POST["address"]);
  $phone = htmlspecialchars($_POST["phone"]);
  $weight = htmlspecialchars($_POST["weight"]);
  $height = htmlspecialchars($_POST["height"]);
  $username = htmlspecialchars($_POST["username"]);
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
  $role = "customer";

  // Check for duplicate email or username
  $check = $conn->prepare("SELECT * FROM users1 WHERE Email = ? OR Username = ?");
  $check->bind_param("ss", $email, $username);
  $check->execute();
  $result = $check->get_result();

  if ($result->num_rows > 0) {
    $error = "Email or Username already exists.";
  } else {
    // Insert new customer (Admission Number is auto-incremented)
    $sql = "INSERT INTO users1 (Full_Name, NIC, Email, Address, Phone_Number, Weight, Height, Username, Password, Role)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $fullname, $nic, $email, $address, $phone, $weight, $height, $username, $password, $role);

    if ($stmt->execute()) {
      $success = "Registration successful! <a href='login.php'>Click here to login</a>.";
    } else {
      $error = "Something went wrong. Please try again.";
    }
    $stmt->close();
  }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Register | FitZone</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #36454F;
      color: #ffffff;
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

    .register-container {
      max-width: 500px;
      margin: 50px auto;
      background-color: #fff;
      color: #333;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    h2 {
      text-align: center;
      color: #ff5722;
      margin-bottom: 20px;
    }

    label {
      font-weight: bold;
      display: block;
      margin-top: 15px;
    }

    input, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    button {
      width: 100%;
      padding: 10px;
      margin-top: 20px;
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
      margin-top: 15px;
      font-weight: bold;
    }

    .message.success {
      color: green;
    }

    .message.error {
      color: red;
    }

    .footer {
      background-color: #ff5722;
      color: white;
      text-align: center;
      padding: 15px 0;
      margin-top: 50px;
    }
  </style>
</head>

<body>
	<!-- Navigation Bar -->
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

<!-- Registration Form -->
<div class="register-container">
  <h2>Create an Account</h2>

  <?php
    if (!empty($success)) echo "<div class='message success'>$success</div>";
    if (!empty($error)) echo "<div class='message error'>$error</div>";
  ?>

  <form method="POST">
    <label>Full Name:</label>
    <input type="text" name="fullname" required>
<label>NIC:</label>
    <input type="text" name="nic" required>

    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Address:</label>
    <textarea name="address" rows="2" required></textarea>

    <label>Phone Number:</label>
    <input type="text" name="phone" required>

    <label>Weight (kg):</label>
    <input type="number" name="weight" required>

    <label>Height (cm):</label>
    <input type="number" name="height" required>

    <label>Username:</label>
    <input type="text" name="username" required>

    <label>Password:</label>
    <input type="password" name="password" required>

    <button type="submit">Register</button>
  </form>
</div>

<!-- Footer -->
<div class="footer">
  <p>&copy; <?php echo date("Y"); ?> FitZone Fitness Center. All rights reserved.</p>
</div>
</body>
</html>
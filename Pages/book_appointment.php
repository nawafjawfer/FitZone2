<?php
session_start();
// Connect to database
$conn = new mysqli("localhost", "root", "", "fitzone1");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Only allow logged-in customers
if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "customer") {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION["user_id"];
$success = "";
$error = "";

// Handle appointment form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $class = $_POST["class"];
  $date = $_POST["date"];
  $time = $_POST["time"];

  $stmt = $conn->prepare("INSERT INTO appointments (Admission_Number, Class_Type, Preferred_Date, Preferred_Time) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("isss", $user_id, $class, $date, $time);

  if ($stmt->execute()) {
    $success = "✅ Appointment booked successfully!";
  } else {
    $error = "❌ Error booking appointment.";
  }

  $stmt->close();
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Appointment | FitZone</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #36454F;
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

    select, input[type="date"], input[type="time"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 5px;
      border: 1px solid #ccc;
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
      margin-top: 150px;
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

  <!-- Main Content -->
  <div class="container">
    <h2>Book a Fitness Class</h2>

    <?php
      if (!empty($success)) echo "<div class='message success'>$success</div>";
      if (!empty($error)) echo "<div class='message error'>$error</div>";
    ?>

    <form method="POST">
      <label>Class Type:</label>
      <select name="class" required>
        <option value="Cardio">Cardio</option>
        <option value="Strength Training">Strength Training</option>
        <option value="Yoga">Yoga</option>
        <option value="HIIT">HIIT</option>
      </select>

      <label>Preferred Date:</label>
      <input type="date" name="date" min="<?php echo date('Y-m-d'); ?>" required>

      <label>Preferred Time:</label>
      <input type="time" name="time" required>

      <button type="submit">Book Appointment</button>
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
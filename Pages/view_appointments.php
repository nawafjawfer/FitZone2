<?php
session_start();
// Connect to database
$conn = new mysqli("localhost", "root", "", "fitzone1");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "customer") {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION["user_id"];
$success = "";
$error = "";

// Handle cancel
if (isset($_POST["cancel_id"])) {
  $appointment_id = $_POST["cancel_id"];
  $check = $conn->prepare("SELECT Created_At FROM appointments WHERE Appointment_ID = ? AND Admission_Number = ?");
  $check->bind_param("ii", $appointment_id, $user_id);
  $check->execute();
  $res = $check->get_result();
  if ($res->num_rows === 1) {
    $row = $res->fetch_assoc();
    if (time() - strtotime($row['Created_At']) <= 1800) {
      $delete = $conn->prepare("DELETE FROM appointments WHERE Appointment_ID = ?");
      $delete->bind_param("i", $appointment_id);
      if ($delete->execute()) {
        $success = "❌ Appointment cancelled.";
      } else {
        $error = "Failed to cancel appointment.";
      }
      $delete->close();
    } else {
      $error = "⏱️ You can only cancel within 30 minutes of booking.";
    }
  }
  $check->close();
}

// Handle update
if (isset($_POST["update_appointment"])) {
  $id = $_POST["update_id"];
  $new_class = $_POST["class"];
  $new_date = $_POST["date"];
  $new_time = $_POST["time"];

  $check = $conn->prepare("SELECT Created_At FROM appointments WHERE Appointment_ID = ? AND Admission_Number = ?");
  $check->bind_param("ii", $id, $user_id);
  $check->execute();
  $res = $check->get_result();

  if ($res->num_rows === 1) {
    $row = $res->fetch_assoc();
    if (time() - strtotime($row["Created_At"]) <= 1800) {
      $update = $conn->prepare("UPDATE appointments SET Class_Type = ?, Preferred_Date = ?, Preferred_Time = ? WHERE Appointment_ID = ?");
      $update->bind_param("sssi", $new_class, $new_date, $new_time, $id);
      if ($update->execute()) {
        $success = "✅ Appointment updated successfully.";
      } else {
        $error = "Failed to update appointment.";
      }
      $update->close();
    } else {
      $error = "⚠️ Update window expired (30 minutes).";
    }
  }
  $check->close();
}

// Fetch appointments
$stmt = $conn->prepare("SELECT * FROM appointments WHERE Admission_Number = ? ORDER BY Preferred_Date, Preferred_Time");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Appointments | FitZone</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
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
      max-width: 1000px;
      margin: 60px auto;
      background: rgba(255,255,255,0.97);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.3);
    }

    h2 {
      color: #ff5722;
      text-align: center;
    }

    .message {
      text-align: center;
      font-weight: bold;
      margin-top: 10px;
    }

    .success { color: green; }
    .error { color: red; }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: center;
    }

    th {
      background-color: #ff5722;
      color: white;
    }

    input, select {
      padding: 6px;
      width: 100%;
      margin: 4px 0;
      box-sizing: border-box;
    }

    .btn {
      padding: 6px 12px;
      border: none;
      border-radius: 4px;
      color: white;
      cursor: pointer;
      font-size: 14px;
    }

    .btn-update { background-color: #28a745; }
    .btn-cancel { background-color: #dc3545; }
    .btn:hover { opacity: 0.9; }

    .locked {
      color: gray;
      font-style: italic;
    }

    .back {
      text-align: center;
      margin-top: 25px;
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
      margin-top: 310px;
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

<!-- Container -->
<div class="container">
  <h2>My Appointments</h2>

  <?php
    if (!empty($success)) echo "<div class='message success'>$success</div>";
    if (!empty($error)) echo "<div class='message error'>$error</div>";
  ?>

  <table>
    <tr>
      <th>Class</th>
      <th>Date</th>
      <th>Time</th>
      <th>Status</th>
      <th>Booked At</th>
      <th>Actions</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()):
      $created_time = strtotime($row['Created_At']);
      $can_modify = (time() - $created_time <= 1800);
    ?>
      <tr>
        <td><?php echo htmlspecialchars($row['Class_Type']); ?></td>
        <td><?php echo $row['Preferred_Date']; ?></td>
        <td><?php echo $row['Preferred_Time']; ?></td>
        <td><?php echo $row['Status']; ?></td>
        <td><?php echo date("Y-m-d H:i", $created_time); ?></td>
        <td>
          <?php if ($can_modify): ?>
            <form method="POST" style="margin-bottom: 8px;">
              <input type="hidden" name="update_id" value="<?php echo $row['Appointment_ID']; ?>">
              <select name="class" required>
                <option value="Cardio" <?php if ($row['Class_Type'] == 'Cardio') echo 'selected'; ?>>Cardio</option>
                <option value="Strength Training" <?php if ($row['Class_Type'] == 'Strength Training') echo 'selected'; ?>>Strength Training</option>
                <option value="Yoga" <?php if ($row['Class_Type'] == 'Yoga') echo 'selected'; ?>>Yoga</option>
                <option value="HIIT" <?php if ($row['Class_Type'] == 'HIIT') echo 'selected'; ?>>HIIT</option>
              </select>
              <input type="date" name="date" value="<?php echo $row['Preferred_Date']; ?>" required>
              <input type="time" name="time" value="<?php echo $row['Preferred_Time']; ?>" required>
              <button type="submit" name="update_appointment" class="btn btn-update">Update</button>
            </form>
            <form method="POST" onsubmit="return confirm('Cancel this appointment?');">
              <input type="hidden" name="cancel_id" value="<?php echo $row['Appointment_ID']; ?>">
              <button type="submit" class="btn btn-cancel">Cancel</button>
            </form>
          <?php else: ?>
            <div class="locked">Locked (after 30 mins)</div>
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

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
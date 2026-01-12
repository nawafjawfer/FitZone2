<?php
session_start();
// Connect to database
$conn = new mysqli("localhost", "root", "", "fitzone1");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Allow gym staff only
if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "gym_management_staff") {
  header("Location: login.php");
  exit();
}

// Handle appointment status update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['appointment_id'], $_POST['status'])) {
  $appointment_id = $_POST['appointment_id'];
  $new_status = $_POST['status'];

  $update = $conn->prepare("UPDATE appointments SET Status = ? WHERE Appointment_ID = ?");
  $update->bind_param("si", $new_status, $appointment_id);
  $update->execute();
}

// Fetch all appointments
$sql = "SELECT a.*, u.Full_Name 
        FROM appointments a 
        JOIN users1 u ON a.Admission_Number = u.Admission_Number 
        ORDER BY a.Preferred_Date, a.Preferred_Time";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Appointments | Gym Staff</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #36454F;
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
      font-size: 24px;
      font-weight: bold;
    }

    .navbar a {
      color: white;
      margin-left: 15px;
      text-decoration: none;
    }

    .navbar a:hover {
      text-decoration: underline;
    }

    .container {
      max-width: 1000px;
      margin: 40px auto;
      background: white;
      padding: 30px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    h2 {
      text-align: center;
      color: #ff5722;
      margin-bottom: 25px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 12px;
      vertical-align: middle;
      text-align: center;
    }

    th {
      background-color: #ff5722;
      color: white;
    }

    select, button {
      padding: 7px 10px;
      border-radius: 4px;
      border: 1px solid #999;
    }

    button {
      background-color: #ff5722;
      color: white;
      border: none;
      cursor: pointer;
    }

    button:hover {
      background-color: #e64a19;
    }

    .back {
      text-align: center;
      margin-top: 30px;
    }

    .back a {
      text-decoration: none;
      color: #ff5722;
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
      <a href="staff_dashboard.php">Dashboard</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <!-- Container -->
  <div class="container">
    <h2>Manage Class Appointments</h2>

    <table>
      <tr>
        <th>Customer</th>
        <th>Class</th>
        <th>Date</th>
        <th>Time</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['Full_Name']); ?></td>
          <td><?php echo htmlspecialchars($row['Class_Type']); ?></td>
          <td><?php echo $row['Preferred_Date']; ?></td>
          <td><?php echo $row['Preferred_Time']; ?></td>
          <td><?php echo $row['Status']; ?></td>
          <td>
            <form method="POST" style="display:inline;">
              <input type="hidden" name="appointment_id" value="<?php echo $row['Appointment_ID']; ?>">
              <select name="status" required>
                <option value="Pending" <?php if($row['Status']=='Pending') echo "selected"; ?>>Pending</option>
                <option value="Approved" <?php if($row['Status']=='Approved') echo "selected"; ?>>Approved</option>
                <option value="Cancelled" <?php if($row['Status']=='Cancelled') echo "selected"; ?>>Cancelled</option>
                <option value="Completed" <?php if($row['Status']=='Completed') echo "selected"; ?>>Completed</option>
              </select>
              <button type="submit">Update</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>

    <div class="back">
      <a href="staff_dashboard.php">← Back to Staff Dashboard</a>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <p>&copy; <?php echo date("Y"); ?> FitZone Fitness Center. All rights reserved.</p>
  </div>

</body>
</html>
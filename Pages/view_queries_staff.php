<?php 
session_start();

// Connect to database
$conn = new mysqli("localhost", "root", "", "fitzone1");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Only allow access for gym staff
if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "gym_management_staff") {
  header("Location: login.php");
  exit();
}

// Handle reply
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["query_id"], $_POST["response"])) {
  $query_id = $_POST["query_id"];
  $response = trim($_POST["response"]);
  $stmt = $conn->prepare("UPDATE queries SET Response = ?, Responded_At = NOW() WHERE Query_ID = ?");
  $stmt->bind_param("si", $response, $query_id);
  $stmt->execute();
}

// Fetch all queries
$results = $conn->query("SELECT q.*, u.Full_Name FROM queries q JOIN users1 u ON u.Admission_Number = q.Admission_Number ORDER BY q.Submitted_At DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Respond to Queries | Gym Staff</title>
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
      padding: 10px;
      vertical-align: top;
    }

    th {
      background-color: #ff5722;
      color: white;
    }

    textarea {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    button {
      margin-top: 10px;
      padding: 8px 16px;
      background-color: #ff5722;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    button:hover {
      background-color: #e64a19;
    }

    .response-box {
      background-color: #e6ffe6;
      padding: 10px;
      border-left: 4px solid green;
      border-radius: 5px;
      margin-top: 5px;
    }

    .back {
      text-align: center;
      margin-top: 30px;
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
      <a href="staff_dashboard.php">Dashboard</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <!-- Main Container -->
  <div class="container">
    <h2>Respond to Customer Queries</h2>

    <table>
      <tr>
        <th>Customer</th>
        <th>Subject</th>
        <th>Message</th>
        <th>Submitted</th>
        <th>Response</th>
      </tr>
      <?php while ($row = $results->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($row["Full_Name"]); ?></td>
          <td><?php echo htmlspecialchars($row["Subject"]); ?></td>
          <td><?php echo nl2br(htmlspecialchars($row["Message"])); ?></td>
          <td><?php echo $row["Submitted_At"]; ?></td>
          <td>
            <?php if (!empty($row["Response"])): ?>
              <div class="response-box">
                <?php echo nl2br(htmlspecialchars($row["Response"])); ?><br>
                <small><em>Responded at: <?php echo $row["Responded_At"]; ?></em></small>
              </div>
            <?php else: ?>
              <form method="POST">
                <textarea name="response" rows="3" placeholder="Type your reply..." required></textarea>
                <input type="hidden" name="query_id" value="<?php echo $row["Query_ID"]; ?>">
                <button type="submit">Send Reply</button>
              </form>
            <?php endif; ?>
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
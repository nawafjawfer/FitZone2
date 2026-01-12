
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

// Handle plan purchase
if (isset($_POST["purchase_plan"])) {
  $plan_id = $_POST["plan_id"];
  $today = date("Y-m-d");

  // Optional: prevent duplicate same-day plan purchases
  
  $check_duplicate = $conn->prepare("SELECT * FROM memberships WHERE Admission_Number = ? AND Plan_ID = ? AND Start_Date = ?");
  $check_duplicate->bind_param("iis", $user_id, $plan_id, $today);
  $check_duplicate->execute();
  if ($check_duplicate->get_result()->num_rows > 0) {
    header("Location: view_membership.php?error=already");
    exit();
  }

  $get_plan = $conn->prepare("SELECT Duration_Days FROM membership_plans WHERE Plan_ID = ?");
  $get_plan->bind_param("i", $plan_id);
  $get_plan->execute();
  $plan_res = $get_plan->get_result();
  if ($plan = $plan_res->fetch_assoc()) {
    $duration = $plan['Duration_Days'];
    $end_date = date("Y-m-d", strtotime("+$duration days"));

    $insert = $conn->prepare("INSERT INTO memberships (Admission_Number, Plan_ID, Start_Date, End_Date) VALUES (?, ?, ?, ?)");
    $insert->bind_param("iiss", $user_id, $plan_id, $today, $end_date);
    if ($insert->execute()) {
      header("Location: view_membership.php?success=1");
      exit();
    } else {
      $error = "Error purchasing plan.";
    }
    $insert->close();
  }
}

// Fetch current (latest) plan
$check = $conn->prepare("SELECT mp.*, m.Start_Date, m.End_Date 
                         FROM memberships m 
                         JOIN membership_plans mp ON mp.Plan_ID = m.Plan_ID 
                         WHERE m.Admission_Number = ? 
                         ORDER BY m.End_Date DESC LIMIT 1");
$check->bind_param("i", $user_id);
$check->execute();
$membership_result = $check->get_result();
$current_membership = $membership_result->fetch_assoc();

// Fetch all plans
$plans = $conn->query("SELECT * FROM membership_plans");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Membership | FitZone</title>
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
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
    }

    h2, h3 {
      text-align: center;
      color: #ff5722;
    }

    .message {
      text-align: center;
      font-weight: bold;
      margin-top: 10px;
    }

    .success { color: green; }
    .error { color: red; }

    .plan {
      border: 1px solid #ccc;
      padding: 20px;
      border-radius: 8px;
      margin-top: 20px;
      background-color: #f9f9f9;
    }

    .current {
      background-color: #fff7e6;
    }

    .btn {
      padding: 10px 20px;
      background-color: #ff5722;
      color: white;
      border: none;
      border-radius: 5px;
      margin-top: 10px;
      cursor: pointer;
    }

    .btn:hover {
      background-color: #e64a19;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: center;
    }

    th {
      background-color: #ff5722;
      color: white;
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
      margin-top: 60px;
    }
  </style>
  <script>
    function confirmPayment(plan, price) {
      return confirm(`💳 Simulate payment\n\nPlan: ${plan}\nPrice: Rs. ${price}\n\nClick OK to proceed.`);
    }
  </script>
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

<div class="container">
  <h2>My Membership</h2>

  <?php if (isset($_GET['success'])) echo "<p class='message success'>✅ Plan purchased successfully!</p>"; ?>
  <?php if (isset($_GET['error']) && $_GET['error'] == 'already') echo "<p class='message error'>⚠️ You already purchased this plan today.</p>"; ?>
  <?php if (!empty($error)) echo "<p class='message error'>$error</p>"; ?>

  <?php if ($current_membership): ?>
    <?php
      $today = date("Y-m-d");
      $end = $current_membership['End_Date'];
      $remaining_days = max(0, floor((strtotime($end) - strtotime($today)) / (60 * 60 * 24)));
    ?>
    <div class="plan current">
      <h4>📋 Current Plan: <?php echo htmlspecialchars($current_membership['Plan_Name']); ?></h4>
      <p><strong>Start Date:</strong> <?php echo $current_membership['Start_Date']; ?></p>
      <p><strong>End Date:</strong> <?php echo $end; ?></p>
      <p><strong>Days Remaining:</strong> <?php echo $remaining_days; ?> day(s)</p>
      <p><strong>Benefits:</strong><br><?php echo nl2br($current_membership['Benefits']); ?></p>
    </div>
  <?php else: ?>
    <p class="message">You have not purchased any membership plan yet.</p>
  <?php endif; ?>

  <h3>Available Plans</h3>
  <?php while ($plan = $plans->fetch_assoc()): ?>
    <div class="plan">
      <h4><?php echo htmlspecialchars($plan['Plan_Name']); ?></h4>
      <p><strong>Price:</strong> Rs. <?php echo number_format($plan['Price'], 2); ?></p>
      <p><strong>Duration:</strong> <?php echo $plan['Duration_Days']; ?> days</p>
      <p><strong>Benefits:</strong><br><?php echo nl2br($plan['Benefits']); ?></p>
      <form method="POST" onsubmit="return confirmPayment('<?php echo addslashes($plan['Plan_Name']); ?>', '<?php echo $plan['Price']; ?>')">
        <input type="hidden" name="plan_id" value="<?php echo $plan['Plan_ID']; ?>">
        <button type="submit" name="purchase_plan" class="btn">Purchase This Plan</button>
      </form>
    </div>
  <?php endwhile; ?>

  <h3>Membership History</h3>
  <table>
    <tr>
      <th>Plan</th>
      <th>Start</th>
      <th>End</th>
      <th>Status</th>
    </tr>
    <?php
    $today = date("Y-m-d");
    $history_stmt = $conn->prepare("SELECT mp.Plan_Name, m.Start_Date, m.End_Date 
                                    FROM memberships m 
                                    JOIN membership_plans mp ON mp.Plan_ID = m.Plan_ID 
                                    WHERE m.Admission_Number = ? 
                                    ORDER BY m.End_Date DESC");
    $history_stmt->bind_param("i", $user_id);
    $history_stmt->execute();
    $history_result = $history_stmt->get_result();
    while ($mem = $history_result->fetch_assoc()):
      $status = (strtotime($mem['End_Date']) >= strtotime($today)) ? "Active" : "Expired";
    ?>
      <tr>
        <td><?php echo htmlspecialchars($mem['Plan_Name']); ?></td>
        <td><?php echo $mem['Start_Date']; ?></td>
        <td><?php echo $mem['End_Date']; ?></td>
        <td><?php echo $status; ?></td>
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

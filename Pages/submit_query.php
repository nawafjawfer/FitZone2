<?php
session_start();
$conn = new mysqli("localhost", "root", "", "fitzone1");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "customer") {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION["user_id"];

// Handle submission with PRG (Post/Redirect/Get)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $subject = trim($_POST["subject"]);
  $message = trim($_POST["message"]);

  if (empty($subject) || empty($message)) {
    header("Location: submit_query.php?error=1");
    exit();
  } else {
    $stmt = $conn->prepare("INSERT INTO queries (Admission_Number, Subject, Message) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $subject, $message);
    if ($stmt->execute()) {
      header("Location: submit_query.php?success=1");
      exit();
    } else {
      header("Location: submit_query.php?error=2");
      exit();
    }
  }
}

// Fetch user queries
$queries = $conn->prepare("SELECT * FROM queries WHERE Admission_Number = ? ORDER BY Submitted_At DESC");
$queries->bind_param("i", $user_id);
$queries->execute();
$query_results = $queries->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Submit Query | FitZone</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background-color: #f4f4f4;
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
      text-decoration: none;
      margin-left: 15px;
      font-size: 16px;
    }

    .navbar a:hover {
      text-decoration: underline;
    }

    .container {
      max-width: 900px;
      background: white;
      margin: 40px auto;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h2, h3 {
      text-align: center;
      color: #ff5722;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }

    input[type="text"], textarea {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    button {
      background-color: #ff5722;
      color: white;
      border: none;
      padding: 10px 20px;
      margin-top: 20px;
      font-size: 16px;
      border-radius: 4px;
      cursor: pointer;
    }

    button:hover {
      background-color: #e64a19;
    }

    .message {
      text-align: center;
      font-weight: bold;
      margin-top: 10px;
    }

    .success {
      color: green;
    }

    .error {
      color: red;
    }

    table {
      width: 100%;
      margin-top: 30px;
      border-collapse: collapse;
    }

    th, td {
      padding: 10px;
      border: 1px solid #ccc;
      vertical-align: top;
      text-align: left;
    }

    th {
      background-color: #ff5722;
      color: white;
    }

    .response {
      color: green;
    }

    .pending {
      color: gray;
      font-style: italic;
    }

    .back {
      text-align: center;
      margin-top: 20px;
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
    <a href="programs.php">Programs</a>
    <a href="trainers.php">Trainers</a>
    <a href="membership.php">Membership</a>
    <a href="contact.php">Contact</a>
    <a href="logout.php">Logout</a>
  </div>
</div>

<div class="container">
  <h2>Submit a Query</h2>

  <!-- Feedback -->
  <?php if (isset($_GET['success'])): ?>
    <p class="message success">✅ Your query has been submitted successfully!</p>
  <?php elseif (isset($_GET['error'])): ?>
    <p class="message error">
      <?php
        if ($_GET['error'] == 1) echo "Please fill in both subject and message.";
        elseif ($_GET['error'] == 2) echo "❌ Something went wrong. Please try again.";
      ?>
    </p>
  <?php endif; ?>

  <!-- Submit Form -->
  <form method="POST">
    <label>Subject</label>
    <input type="text" name="subject" required>

    <label>Message</label>
    <textarea name="message" rows="5" required></textarea>

    <button type="submit">Submit Query</button>
  </form>

  <!-- Query List -->
  <h3>Your Previous Queries</h3>
  <table>
    <tr>
      <th>Subject</th>
      <th>Message</th>
      <th>Submitted</th>
      <th>Response</th>
    </tr>
    <?php while ($row = $query_results->fetch_assoc()): ?>
    <tr>
      <td><?php echo htmlspecialchars($row['Subject']); ?></td>
      <td><?php echo nl2br(htmlspecialchars($row['Message'])); ?></td>
      <td><?php echo $row['Submitted_At']; ?></td>
      <td>
        <?php if ($row['Response']): ?>
          <div class="response">
            <?php echo nl2br(htmlspecialchars($row['Response'])); ?><br>
            <small><em>Responded at: <?php echo $row['Responded_At']; ?></em></small>
          </div>
        <?php else: ?>
          <span class="pending">Pending response</span>
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
  &copy; <?php echo date("Y"); ?> FitZone Fitness Center. All rights reserved.
</div>

</body>
</html>
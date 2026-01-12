<?php 
session_start();
$conn = new mysqli("localhost", "root", "", "fitzone1");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] !== "admin") {
  header("Location: login.php");
  exit();
}

$message = "";

// Handle role update
if (isset($_POST["update_role"])) {
  $user_id = $_POST["user_id"];
  $new_role = $_POST["role"];
  $update = $conn->prepare("UPDATE users1 SET Role = ? WHERE Admission_Number = ?");
  $update->bind_param("si", $new_role, $user_id);
  $update->execute();
  $update->close();
  $message = "✅ Role updated successfully!";
}

// Handle delete
if (isset($_POST["delete_user"])) {
  $user_id = $_POST["user_id"];
  $delete = $conn->prepare("DELETE FROM users1 WHERE Admission_Number = ?");
  $delete->bind_param("i", $user_id);
  $delete->execute();
  $delete->close();
  $message = "🗑️ User deleted successfully.";
}

// Handle search
$search_name = $_GET['search_name'] ?? '';
$search_role = $_GET['search_role'] ?? '';
$sql = "SELECT * FROM users1 WHERE 1";
if (!empty($search_name)) {
  $sql .= " AND Full_Name LIKE '%" . $conn->real_escape_string($search_name) . "%'";
}
if (!empty($search_role)) {
  $sql .= " AND Role = '" . $conn->real_escape_string($search_role) . "'";
}
$sql .= " ORDER BY Admission_Number ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>View Users | Admin Panel</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #36454F;
      margin: 0;
      padding: 0;
      color: #333;
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
      margin: 50px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #ff5722;
    }

    .message {
      text-align: center;
      margin-bottom: 20px;
      font-weight: bold;
      color: green;
    }

    form.search-form {
      text-align: center;
      margin-bottom: 20px;
    }

    form.search-form input,
    form.search-form select {
      padding: 8px;
      margin: 0 5px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: left;
    }

    th {
      background-color: #ff5722;
      color: white;
    }

    .btn {
      padding: 6px 12px;
      border-radius: 4px;
      border: none;
      cursor: pointer;
    }

    .btn-update {
      background-color: #007bff;
      color: white;
    }

    .btn-update:hover {
      background-color: #0056b3;
    }

    .btn-danger {
      background-color: red;
      color: white;
    }

    .btn-danger:hover {
      background-color: darkred;
    }

    .back-link {
      text-align: center;
      margin-top: 25px;
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
    <a href="login.php">Login</a>
  </div>
</div>

<div class="container">
  <h2>All Users & Role Management</h2>

  <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>

  <form class="search-form" method="GET">
    <input type="text" name="search_name" placeholder="Search by name" value="<?php echo htmlspecialchars($search_name); ?>">
    <select name="search_role">
      <option value="">All Roles</option>
      <option value="customer" <?php if ($search_role == 'customer') echo 'selected'; ?>>Customer</option>
      <option value="gym_management_staff" <?php if ($search_role == 'gym_management_staff') echo 'selected'; ?>>Gym Staff</option>
      <option value="admin" <?php if ($search_role == 'admin') echo 'selected'; ?>>Admin</option>
    </select>
    <button type="submit" class="btn btn-update">Search</button>
  </form>

  <table>
    <tr>
      <th>Admission No</th>
      <th>Full Name</th>
      <th>NIC</th>
      <th>Email</th>
      <th>Username</th>
      <th>Role</th>
      <th>Update Role</th>
      <th>Delete</th>
    </tr>

    <?php while ($user = $result->fetch_assoc()): ?>
      <?php $admission = "C" . str_pad($user['Admission_Number'], 3, "0", STR_PAD_LEFT); ?>
      <tr>
        <td><?php echo $admission; ?></td>
        <td><?php echo htmlspecialchars($user['Full_Name']); ?></td>
        <td><?php echo htmlspecialchars($user['NIC']); ?></td>
        <td><?php echo htmlspecialchars($user['Email']); ?></td>
        <td><?php echo htmlspecialchars($user['Username']); ?></td>
        <td><?php echo htmlspecialchars($user['Role']); ?></td>
        <td>
          <form method="POST" style="display:inline;">
            <input type="hidden" name="user_id" value="<?php echo $user['Admission_Number']; ?>">
            <select name="role" required>
              <option value="customer" <?php if($user['Role']=='customer') echo 'selected'; ?>>Customer</option>
              <option value="gym_management_staff" <?php if($user['Role']=='gym_management_staff') echo 'selected'; ?>>Gym Staff</option>
              <option value="admin" <?php if($user['Role']=='admin') echo 'selected'; ?>>Admin</option>
            </select>
            <button type="submit" name="update_role" class="btn btn-update">Update</button>
          </form>
        </td>
        <td>
          <form method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
            <input type="hidden" name="user_id" value="<?php echo $user['Admission_Number']; ?>">
            <button type="submit" name="delete_user" class="btn btn-danger">Delete</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <div class="back-link">
    <a href="admin_dashboard.php">← Back to Admin Dashboard</a>
  </div>
</div>

<div class="footer">
  <p>&copy; <?php echo date("Y"); ?> FitZone Fitness Center. All rights reserved.</p>
</div>

</body>
</html>
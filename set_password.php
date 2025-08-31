<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST['password'];
    $confirm  = $_POST['confirmPassword'];

    if($password !== $confirm) {
        $error = "Passwords do not match!";
    } elseif (!isset($_SESSION['temp_user_id'])) {
        $error = "Session expired. Please sign up again.";
    } else {
        $user_id = $_SESSION['temp_user_id'];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Get account ID
        $stmt = $conn->prepare("SELECT account_id FROM Users WHERE user_id=?");
        $stmt->bind_param("i",$user_id);
        $stmt->execute();
        $stmt->bind_result($account_id);
        $stmt->fetch();
        $stmt->close();

        // Update password
        $update = $conn->prepare("UPDATE Users SET user_password=? WHERE user_id=?");
        $update->bind_param("si",$hashedPassword,$user_id);
        $update->execute();

        unset($_SESSION['temp_user_id']);

        // Show popup with account ID and password
        echo "<script>
            alert('Account created!\\nAccount ID: $account_id\\nPassword: $password');
            window.location.href='login.php';
        </script>";
        exit;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Set Password - ThriftTipid</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet" />
</head>
<body>
  <input type="checkbox" id="theme-toggle" hidden />
  <div class="page">
    <label for="theme-toggle" class="theme-button">
      <span class="material-icons light-icon">light_mode</span>
      <span class="material-icons dark-icon">dark_mode</span>
    </label>

    <div class="background-blur"></div>

    <div class="content-wrapper">
      <!-- Left side: Welcome message -->
      <div class="left-side">
        <div class="welcome-message">
          <h1>Welcome to ThriftTipid</h1>
          <p>A student expenses tracker system web app to your convenience</p>
         
          <div class="features-section">
            <h2>Features</h2>
            <ul class="features-list">
              <li>Savings Goal</li>
              <li>Budget List</li>
              <li>Expenses List</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Right side: Set Password Form -->
      <div class="inFormBackground">
        <div class="inLoginForm">
          <?php if (!empty($error)): ?>
            <p style="color:red; text-align:center;"><?= htmlspecialchars($error) ?></p>
          <?php endif; ?>

          <form method="POST" action="set_password.php">
            <div class="title">
              <h3 class="login-title">Set Password</h3>
            </div>

            <div class="inputGroup">
              <label for="password">Password</label>
              <input type="password" placeholder="Enter Password" id="password" name="password" required />
            </div>

            <div class="inputGroup">
              <label for="confirmPassword">Confirm Password</label>
              <input type="password" placeholder="Confirm Password" id="confirmPassword" name="confirmPassword" required />
            </div>

            <div class="button-container">
              <button type="submit" class="submitForm">Create Account</button>
              <div class="signup-row">
                <p class="new-account">Already have an Account?</p>
                <a href="login.php" class="submitForm signup-btn">Log In</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

<script src="theme.js"></script>
</body>
</html>

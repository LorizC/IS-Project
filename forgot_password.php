<?php
ob_start();
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $accountID        = $_POST['account_id'];
    $securityQuestion = $_POST['security_question'];
    $securityAnswer   = $_POST['security_answer'];
    $password         = $_POST['password'];
    $confirmPassword  = $_POST['confirmPassword'];

    // Check if new passwords match
    if ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Check if account exists with matching security question & answer
        $stmt = $conn->prepare("SELECT user_id FROM Users WHERE account_id=? AND security_question=? AND security_answer=?");
        $stmt->bind_param("sss", $accountID, $securityQuestion, $securityAnswer);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // Hash the new password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Update password
            $update = $conn->prepare("UPDATE Users SET user_password=? WHERE account_id=?");
            $update->bind_param("ss", $hashedPassword, $accountID);

            if ($update->execute()) {
                $success = "Password changed successfully. You can now login.";
            } else {
                $error = "Error updating password. Please try again.";
            }
        } else {
            $error = "Invalid account details or security answer.";
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Welcome to ThriftTipid</title>
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

      <!-- Right side: Login form -->
      <div class="inFormBackground">
        <div class="inLoginForm">
          <?php if (!empty($error)): ?>
            <p style="color: red; text-align:center;"><?= htmlspecialchars($error) ?></p>
          <?php endif; ?>
          
          <form method="POST" action="">
            <div class="title">
              <h3 class="login-title">Change Password</h3>
            </div>
            <div class="inputGroup">
              <label for="email">Account ID</label>
              <input type="text" placeholder="Enter Account ID" id="email" name="account_id" required />
            </div>
            <div class="inputGroup">
              <label for="security-question">Security Question</label>
              <select id="security-question" name="security_question" required>
                <option value="" disabled selected>Select a question</option>
                <option value="pet">What is the name of your first pet?</option>
                <option value="school">What is the name of your elementary school?</option>
                <option value="food">What is your favorite food?</option>
                <option value="nickname">What was your childhood nickname?</option>
              </select>
            </div>

            <div class="inputGroup">
              <label for="security-answer">Your Answer</label>
              <input type="text" placeholder="Enter your answer" id="security-answer" name="security_answer" required />
            </div>  

            <div class="inputGroup">
              <label for="password">New Password</label>
              <input type="password" placeholder="Enter Password" id="password" name="password" required />
            </div>

            <div class="inputGroup">
              <label for="confirmPassword">Confirm Password</label>
              <input type="password" placeholder="Confirm Password" id="confirmPassword" name="confirmPassword" required />
            </div>

          

            <div class="button-container">
               <button type="submit" class="submitForm">Change Password</button>
              <div class="signup-row">
                <p class="new-account">Changed Password?</p>
                <a href="login.php" class="submitForm signup-btn">Login</a>
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

<?php ob_end_flush(); ?>

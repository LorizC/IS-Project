<?php
ob_start();
session_start();
include 'db_connect.php';

if($_SERVER["REQUEST_METHOD"]==="POST"){
    $accountID = $_POST['account_id'];
    $password  = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, first_name, last_name, user_password FROM Users WHERE account_id=?");
    $stmt->bind_param("s",$accountID);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){
        $row = $result->fetch_assoc();
        if(password_verify($password, $row['user_password'])){
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['name'] = $row['first_name']." ".$row['last_name'];
            header("Location:index.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Account not found.";
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
              <h3 class="login-title">Login Here</h3>
            </div>
            <div class="inputGroup">
              <label for="email">Account ID</label>
              <input type="text" placeholder="Enter Account ID" id="email" name="account_id" required />
            </div>
            <div class="inputGroup">
              <label for="password">Password</label>
              <input type="password" placeholder="Enter Password" id="password" name="password" required />
            </div>

            <div class="button-container">
               <button type="submit" class="submitForm">Log In</button> 
              <div class="signup-row">
                <p class="new-account">Create New Account</p>
                <a href="signup.php" class="submitForm signup-btn">Sign Up</a>
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

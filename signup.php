<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = $_POST['firstName'];
    $lastName  = $_POST['lastName'];
    $birthdate = $_POST['birthdate'];
    $gender    = $_POST['gender'];
    $question  = $_POST['security_question'];
    $answer    = $_POST['security_answer'];

    // Insert user with empty password first
    $stmt = $conn->prepare("INSERT INTO Users (first_name,last_name,birthdate,gender,security_question,security_answer,user_password) VALUES (?,?,?,?,?,?, '')");
    $stmt->bind_param("ssssss", $firstName,$lastName,$birthdate,$gender,$question,$answer);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;

        // Generate unique 6-digit account ID
        do {
            $account_id = str_pad(rand(0,999999),6,"0",STR_PAD_LEFT);
            $check = $conn->prepare("SELECT user_id FROM Users WHERE account_id=?");
            $check->bind_param("s", $account_id);
            $check->execute();
            $check->store_result();
        } while($check->num_rows > 0);

        // Save account ID to database
        $update = $conn->prepare("UPDATE Users SET account_id=? WHERE user_id=?");
        $update->bind_param("si",$account_id,$user_id);
        $update->execute();

        $_SESSION['temp_user_id'] = $user_id; // store user_id for set_password.php
        header("Location: set_password.php");
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
  <title>Create Account - ThriftTipid</title>
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

      <!-- Right side: Sign Up form -->
      <div class="inFormBackground">
        <div class="inLoginForm">
          <form method="POST" action="signup.php">
            <div class="title">
              <h3 class="signup-title">Create Account Here</h3>
            </div>

            <div class="inputGroup">
              <label for="firstName">First Name</label>
              <input type="text" placeholder="Enter First Name" id="firstName" name="firstName" required />
            </div>

            <div class="inputGroup">
              <label for="lastName">Last Name</label>
              <input type="text" placeholder="Enter Last Name" id="lastName" name="lastName" required />
            </div>

            <div class="inputGroup">
              <label for="birthdate">Birthdate</label>
              <input type="date" id="birthdate" name="birthdate" required />
            </div>

            <div class="inputGroup">
              <label for="gender">Gender</label>
              <select id="gender" name="gender" required>
                <option value="" disabled selected>Select your gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Prefer not to say</option>
              </select>
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

            <div class="button-container">
              <button type="submit" class="submitForm">Next</button>
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

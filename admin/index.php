<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();
if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (!empty($_POST["submit"])) {
    $loginquery = "SELECT * FROM admin WHERE (username='$username' OR email='$username') && password='" . md5($password) . "'";
    $result = mysqli_query($db, $loginquery);
    $row = mysqli_fetch_array($result);

    if (is_array($row)) {
      $_SESSION["adm_id"] = $row['adm_id'];
      header("refresh:1;url=dashboard.php");
    } else {
      echo "<script>alert('Invalid Username/Email or Password!');</script>";
    }
  }
}

?>

<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900'>
  <link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Montserrat:400,700'>
  <link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(120deg, #2980b9, #8e44ad);
      height: 100vh;
      font-family: 'Poppins', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .container {
      max-width: 400px;
      background: rgba(255, 255, 255, 0.95);
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 15px 30px rgba(0,0,0,0.2);
    }
    .info h1 {
      color: #2c3e50;
      font-size: 2rem;
      text-align: center;
      margin-bottom: 1.5rem;
      font-weight: 700;
    }
    .form {
      padding: 1rem;
    }
    .thumbnail {
      text-align: center;
      margin-bottom: 1.5rem;
    }
    .thumbnail img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    input[type="text"], input[type="password"] {
      width: 100%;
      padding: 12px;
      margin: 8px 0;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 1rem;
      transition: all 0.3s ease;
    }
    input[type="text"]:focus, input[type="password"]:focus {
      border-color: #2980b9;
      box-shadow: 0 0 5px rgba(41,128,185,0.5);
    }
    input[type="submit"] {
      width: 100%;
      padding: 12px;
      background: #2980b9;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    input[type="submit"]:hover {
      background: #3498db;
    }
    .password-container {
      position: relative;
    }
    .toggle-password {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #666;
    }
    .back-home {
      color: #2980b9;
      text-decoration: none;
      font-size: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-top: 1rem;
      transition: all 0.3s ease;
    }
    .back-home i {
      margin-right: 5px;
    }
    .back-home:hover {
      color: #3498db;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="info">
      <h1>Admin Panel</h1>
    </div>
    <div class="form">
      <div class="thumbnail">
        <h2 style="color: #2c3e50; font-size: 1.5rem; margin-bottom: 1rem;">Welcome Back!!</h2>
        <p style="color: #7f8c8d; font-size: 1rem;">Please login to access the admin panel</p>
      </div>
      <span style="color:red; display:block; text-align:center; margin-bottom:10px;"><?php echo $message; ?></span>
      <span style="color:green; display:block; text-align:center; margin-bottom:10px;"><?php echo $success; ?></span>
      <form class="login-form" action="index.php" method="post">
        <div class="form-group">
          <input type="text" placeholder="Username or Email" name="username" required />
        </div>
        <div class="form-group password-container">
          <input type="password" placeholder="Password" name="password" id="password" required />
          <i class="fa fa-eye-slash toggle-password" onclick="togglePassword()"></i>
        </div>
        <input type="submit" name="submit" value="Login" />
      </form>
      <a href="../index.php" class="back-home">
        <i class="fa fa-arrow-left"></i> Back to Home
      </a>
    </div>
  </div>

  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src='js/index.js'></script>
  <script>
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const toggleIcon = document.querySelector('.toggle-password');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
      } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
      }
    }
  </script>
</body>

</html>
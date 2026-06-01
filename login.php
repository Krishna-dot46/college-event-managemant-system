<!DOCTYPE html>
<html lang="en">
<?php
session_start();
error_reporting(0);
include("connection/connect.php");

if(isset($_POST['submit'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']); 
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if(!empty($username) && !empty($password)) {
    // Use prepared statement to prevent SQL injection
    $stmt = $db->prepare("SELECT * FROM users WHERE (username=? OR email=?) AND password=?");
    $hashed_password = md5($password);
    $stmt->bind_param("sss", $username, $username, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $_SESSION["user_id"] = $row['u_id'];
      header("Location: index.php");
      exit();
    } else {
      $message = "Invalid Username/Email or Password!";
    }
    $stmt->close();
  } else {
    $message = "All fields are required!";
  }
}
?>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="#">
  <title>Login</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/font-awesome.min.css" rel="stylesheet">
  <link href="css/animate.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Onest:wght@100..900&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
    }

    .navbar {
      background: rgba(33, 33, 33, 0.95);
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
      backdrop-filter: blur(5px);
    }

    .widget {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 24px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      padding: 50px;
      margin: auto;
      max-width: 460px;
      transform: translateY(0);
      transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .widget:hover {
      transform: translate(-50%, -52%);
      box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
    }

    .form-control {
      border-radius: 14px;
      border: 2px solid #e0e0e0;
      padding: 16px;
      font-size: 15px;
      transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
      background: rgba(255, 255, 255, 0.95);
      margin-bottom: 22px;
    }

    .form-control:focus {
      border-color: #6366f1;
      box-shadow: 0 0 20px rgba(99, 102, 241, 0.15);
      transform: translateY(-2px);
    }

    .btn-login {
      background: linear-gradient(45deg, #6366f1, #8b5cf6);
      border: none;
      border-radius: 14px;
      padding: 16px 40px;
      font-weight: 600;
      letter-spacing: 1.2px;
      text-transform: uppercase;
      font-size: 15px;
      transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
      color: white;
      width: 100%;
    }

    .btn-login:hover {
      background: linear-gradient(45deg, #4f46e5, #7c3aed);
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(99, 102, 241, 0.25);
    }

    .error-message {
      color: #ef4444;
      font-size: 14px;
      margin: 12px 0;
      text-align: center;
      font-weight: 500;
      display: <?php echo !empty($message) ? 'block' : 'none'; ?>;
    }

    .register-link {
      text-align: center;
      margin-top: 25px;
      font-size: 15px;
      color: #4b5563;
    }

    .register-link a {
      color: #6366f1;
      font-weight: 600;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .register-link a:hover {
      color: #4f46e5;
      text-decoration: underline;
    }

    h3.login-title {
      text-align: center;
      font-family: 'Onest', serif;
      background: linear-gradient(45deg, #6366f1, #8b5cf6);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      color: transparent;
      font-weight: 700;
      font-size: 28px;
      letter-spacing: 1px;
      margin-bottom: 35px;
    }

    .password-container {
      position: relative;
    }

    .password-toggle {
      position: absolute;
      right: 18px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #6366f1;
      transition: all 0.3s ease;
      font-size: 18px;
    }

    .password-toggle:hover {
      color: #4f46e5;
    }

    .nav-link {
      font-weight: 500;
      padding: 12px 25px !important;
      position: relative;
      transition: all 0.3s ease;
    }

    .nav-link:after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: 0;
      left: 0;
      background: #6c5ce7;
      transition: width 0.3s ease;
    }

    .nav-link:hover:after {
      width: 100%;
    }

    .navbar-brand img {
      height: 45px;
      transition: transform 0.3s ease;
    }

    .navbar-brand img:hover {
      transform: scale(1.05);
    }
  </style>
</head>

<body>
  <div style="background-image: url('images/img/pimg.jpg'); width: 100%; background-attachment: fixed; background-size: cover; min-height: 100vh; display: flex; flex-direction: column; position: relative;">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(3px);"></div>
    
    <?php include "inc/navbar.php"; ?>

    <div class="container" style="position: relative; height: calc(100vh - 76px); display: flex; align-items: center; justify-content: center;">
      <div class="widget">
        <h3 class="login-title">Welcome Back!</h3>
        <?php if(!empty($message)): ?>
          <div class="error-message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form action="" method="post">
          <input type="text" class="form-control" placeholder="Username or Email" name="username" required />
          <div class="password-container">
            <input type="password" class="form-control" placeholder="Password" name="password" id="password" required />
            <i class="fa fa-eye password-toggle" onclick="togglePassword('password')"></i>
          </div>
          <button type="submit" name="submit" class="btn-login">Sign In</button>
        </form>
        <div class="register-link">
          New to our platform? <a href="registration.php">Create an account</a>
        </div>
      </div>
    </div>

    <?php include "inc/footer.php"; ?>
  </div>

  <script src="inc/script.js"></script>
  <script src="js/jquery.min.js"></script>
  <script src="js/tether.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/bootstrap-slider.min.js"></script>
  <script src="js/jquery.isotope.min.js"></script>
  <script src="js/headroom.js"></script>
  <script src="js/foodpicky.min.js"></script>
  <script>
    function togglePassword(inputId) {
      const input = document.getElementById(inputId);
      const icon = input.nextElementSibling;

      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    }
  </script>
</body>

</html>
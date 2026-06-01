<!DOCTYPE html>
<html lang="en">
<?php
session_start();
error_reporting(0);
include("connection/connect.php");
$username = $firstname = $lastname = $email = $phone = $password = $confirm_password = "";
$username_err = $firstname_err = $lastname_err = $email_err = $phone_err = $password_err = $confirm_password_err = "";

if (isset($_POST['submit'])) {
   // Validate username
   if (empty(trim($_POST["username"]))) {
      $username_err = "Please enter a username.";
   } else {
      $username = trim($_POST["username"]);
      if (!preg_match('/^[a-zA-Z0-9_]{4,16}$/', $username)) {
         $username_err = "Username must be 4-16 characters and can only contain letters, numbers and underscores.";
      } else {
         // Check if username already exists
         $stmt = $db->prepare("SELECT u_id FROM users WHERE username = ?");
         $stmt->bind_param("s", $username);
         $stmt->execute();
         $result = $stmt->get_result();
         if ($result->num_rows > 0) {
            $username_err = "This username is already taken.";
         }
         $stmt->close();
      }
   }

   // Validate firstname
   if (empty(trim($_POST["firstname"]))) {
      $firstname_err = "Please enter your first name.";
   } else {
      $firstname = trim($_POST["firstname"]);
      if (!preg_match('/^[a-zA-Z]+$/', $firstname)) {
         $firstname_err = "First name can only contain letters.";
      }
   }

   // Validate lastname
   if (empty(trim($_POST["lastname"]))) {
      $lastname_err = "Please enter your last name.";
   } else {
      $lastname = trim($_POST["lastname"]);
      if (!preg_match('/^[a-zA-Z]+$/', $lastname)) {
         $lastname_err = "Last name can only contain letters.";
      }
   }

   // Validate email
   if (empty(trim($_POST["email"]))) {
      $email_err = "Please enter your email address.";
   } else {
      $email = trim($_POST["email"]);
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         $email_err = "Invalid email format.";
      } else {
         $domain = substr(strrchr($email, "@"), 1);
         if ($domain !== "vvwusurat.ac.in") {
            $email_err = "Only @vvwusurat.ac.in email addresses are allowed.";
         } else {
            // Check if email already exists
            $stmt = $db->prepare("SELECT u_id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
               $email_err = "This email is already registered.";
            }
            $stmt->close();
         }
      }
   }

   // Validate phone
   if (empty(trim($_POST["phone"]))) {
      $phone_err = "Please enter your phone number.";
   } else {
      $phone = trim($_POST["phone"]);
      if (!preg_match('/^[0-9]{10}$/', $phone)) {
         $phone_err = "Please enter a valid 10-digit phone number.";
      }
   }

   // Validate password
   if (empty(trim($_POST["password"]))) {
      $password_err = "Please enter a password.";
   } else {
      $password = trim($_POST["password"]);
      if (!preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){8,}$/', $password)) {
         $password_err = "Password must contain at least 8 characters, one lowercase letter, one uppercase letter, one number, and one special character.";
      }
   }

   // Validate confirm password
   if (empty(trim($_POST["cpassword"]))) {
      $confirm_password_err = "Please confirm password.";
   } else {
      $confirm_password = trim($_POST["cpassword"]);
      if (empty($password_err) && ($password != $confirm_password)) {
         $confirm_password_err = "Passwords do not match.";
      }
   }

   // If no errors, proceed with registration
   if (empty($username_err) && empty($firstname_err) && empty($lastname_err) && empty($email_err) && 
       empty($phone_err) && empty($password_err) && empty($confirm_password_err)) {
      
      $stmt = $db->prepare("INSERT INTO users (username, f_name, l_name, email, phone, password) VALUES (?, ?, ?, ?, ?, ?)");
      $hashed_password = md5($password); // Consider using more secure hashing like password_hash()
      $stmt->bind_param("ssssss", $username, $firstname, $lastname, $email, $phone, $hashed_password);
      
      if ($stmt->execute()) {
         $_SESSION['registration_success'] = true;
         header("Location: login.php");
         exit();
      } else {
         $error = "Something went wrong. Please try again later.";
      }
      $stmt->close();
   }
}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="description" content="">
   <meta name="author" content="">
   <link rel="icon" href="#">
   <title>Registration</title>
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
         max-width: 800px;
         transform: translateY(0);
         transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
      }

      .widget:hover {
         transform: translateY(-2px);
         box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
      }

      .form-control {
         border-radius: 14px;
         border: 2px solid #e0e0e0;
         padding: 16px;
         font-size: 15px;
         transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
         background: rgba(255, 255, 255, 0.95);
      }

      .form-control:focus {
         border-color: #6366f1;
         box-shadow: 0 0 20px rgba(99, 102, 241, 0.15);
         transform: translateY(-2px);
      }

      .btn.theme-btn {
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

      .btn.theme-btn:hover {
         background: linear-gradient(45deg, #4f46e5, #7c3aed);
         transform: translateY(-3px);
         box-shadow: 0 8px 25px rgba(99, 102, 241, 0.25);
      }

      span.error {
         color: #ef4444;
         margin-top: 8px;
         margin-bottom: 15px;
         font-weight: 500;
         display: block;
      }

      h3.mb-4 {
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
         margin-bottom: 35px !important;
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

      label {
         font-weight: 500;
         color: #4b5563;
         margin-bottom: 10px;
         display: block;
      }

      textarea.form-control {
         min-height: 120px;
         resize: vertical;
      }

      .col-sm-12.text-center.mt-3 {
         margin-top: 25px;
         font-size: 15px;
         color: #4b5563;
      }

      .col-sm-12.text-center.mt-3 a {
         color: #6366f1;
         font-weight: 600;
         text-decoration: none;
         transition: all 0.3s ease;
      }

      .col-sm-12.text-center.mt-3 a:hover {
         color: #4f46e5;
         text-decoration: underline;
      }
   </style>
</head>

<body>
   <div style="background-image: url('images/circle-bg.png'); background-color: #000000; width: 100%; background-attachment: fixed; background-size: cover; min-height: 100vh; display: flex; flex-direction: column; position: relative;">
      <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(3px);"></div>
      
      <?php include "inc/navbar.php"; ?>

      <div class="container" style="position: relative; height: calc(100vh - 76px); display: flex; align-items: center; justify-content: center; margin-top: 100px; margin-bottom: 100px;">
         <div class="widget">
            <h3 class="mb-4">Create Your Account</h3>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
               <div class="row">
                  <div class="form-group col-sm-12">
                     <label for="exampleInputEmail1">Username</label>
                     <input class="form-control" type="text" name="username" id="example-text-input" value="<?php echo htmlspecialchars($username); ?>" required>
                     <span class="error"><?php echo $username_err; ?></span>
                  </div>
                  <div class="form-group col-sm-6">
                     <label for="exampleInputEmail1">First Name</label>
                     <input class="form-control" type="text" name="firstname" id="example-text-input" value="<?php echo htmlspecialchars($firstname); ?>" required>
                     <span class="error"><?php echo $firstname_err; ?></span>
                  </div>
                  <div class="form-group col-sm-6">
                     <label for="exampleInputEmail1">Last Name</label>
                     <input class="form-control" type="text" name="lastname" id="example-text-input-2" value="<?php echo htmlspecialchars($lastname); ?>" required>
                     <span class="error"><?php echo $lastname_err; ?></span>
                  </div>
                  <div class="form-group col-sm-6">
                     <label for="exampleInputEmail1">Email Address (@vvwusurat.ac.in only)</label>
                     <input type="text" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo htmlspecialchars($email); ?>" required>
                     <span class="error"><?php echo $email_err; ?></span>
                  </div>
                  <div class="form-group col-sm-6">
                     <label for="exampleInputEmail1">Phone number</label>
                     <input class="form-control" type="text" name="phone" id="example-tel-input-3" value="<?php echo htmlspecialchars($phone); ?>" required>
                     <span class="error"><?php echo $phone_err; ?></span>
                  </div>
                  <div class="form-group col-sm-6">
                     <label for="exampleInputPassword1">Password</label>
                     <div class="password-container">
                        <input type="password" class="form-control" name="password" id="exampleInputPassword1" required>
                        <i class="fa fa-eye password-toggle" onclick="togglePassword('exampleInputPassword1')"></i>
                     </div>
                     <span class="error"><?php echo $password_err; ?></span>
                  </div>
                  <div class="form-group col-sm-6">
                     <label for="exampleInputPassword1">Confirm password</label>
                     <div class="password-container">
                        <input type="password" class="form-control" name="cpassword" id="exampleInputPassword2" required>
                        <i class="fa fa-eye password-toggle" onclick="togglePassword('exampleInputPassword2')"></i>
                     </div>
                     <span class="error"><?php echo $confirm_password_err; ?></span>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-12">
                     <button type="submit" name="submit" class="btn theme-btn">Create Account</button>
                  </div>
                  <div class="col-sm-12 text-center mt-3" style="display: flex; justify-content: center; align-items: center;">
                     Already have an account? <a href="login.php" style="margin-left: 5px;">Login here</a>
                  </div>
               </div>
            </form>
         </div>
      </div>

      <div style="margin-top: 470px;">
         <?php include "inc/footer.php"; ?>
      </div>
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
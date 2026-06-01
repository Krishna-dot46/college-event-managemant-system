<!DOCTYPE html>
<html lang="en">
<?php
session_start();
error_reporting(0);
include("connection/connect.php");

// Redirect if not logged in
if(empty($_SESSION["user_id"])) {
    header('location:login.php');
    exit();
}

$user_id = $_SESSION["user_id"];
$success_msg = $error_msg = "";

// Get current user data
$stmt = $db->prepare("SELECT * FROM users WHERE u_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = trim($_POST["firstname"]);
    $lastname = trim($_POST["lastname"]);
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $new_password = trim($_POST["new_password"]);
    $confirm_password = trim($_POST["confirm_password"]);
    
    $error = false;
    
    // Validate inputs
    if(empty($firstname) || !preg_match('/^[a-zA-Z]+$/', $firstname)) {
        $error = true;
        $error_msg = "First name can only contain letters";
    }
    
    if(empty($lastname) || !preg_match('/^[a-zA-Z]+$/', $lastname)) {
        $error = true;
        $error_msg = "Last name can only contain letters";
    }

    if(empty($username) || !preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $error = true;
        $error_msg = "Username can only contain letters, numbers and underscores";
    } else {
        // Check if username already exists for other users
        $stmt = $db->prepare("SELECT u_id FROM users WHERE username = ? AND u_id != ?");
        $stmt->bind_param("si", $username, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0) {
            $error = true;
            $error_msg = "This username is already taken";
        }
        $stmt->close();
    }
    
    if(empty($email)) {
        $error = true;
        $error_msg = "Please enter your email address";
    } else {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = true;
            $error_msg = "Invalid email format";
        } else {
            $domain = substr(strrchr($email, "@"), 1);
            if($domain !== "vvwusurat.ac.in") {
                $error = true;
                $error_msg = "Only @vvwusurat.ac.in email addresses are allowed";
            } else {
                // Check if email already exists for other users
                $stmt = $db->prepare("SELECT u_id FROM users WHERE email = ? AND u_id != ?");
                $stmt->bind_param("si", $email, $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows > 0) {
                    $error = true;
                    $error_msg = "This email is already registered";
                }
                $stmt->close();
            }
        }
    }
    
    if(empty($phone) || !preg_match('/^[0-9]{10}$/', $phone)) {
        $error = true;
        $error_msg = "Please enter a valid 10-digit phone number";
    }

    // If changing password
    if(!empty($new_password) || !empty($confirm_password)) {
        if($new_password !== $confirm_password) {
            $error = true;
            $error_msg = "Passwords do not match";
        }
        if(strlen($new_password) < 6) {
            $error = true;
            $error_msg = "New password must be at least 6 characters";
        }
    }

    if(!$error) {
        if(!empty($new_password)) {
            // Update with new password
            $hashed_password = md5($new_password);
            $stmt = $db->prepare("UPDATE users SET f_name=?, l_name=?, username=?, email=?, phone=?, password=? WHERE u_id=?");
            $stmt->bind_param("ssssssi", $firstname, $lastname, $username, $email, $phone, $hashed_password, $user_id);
        } else {
            // Update without changing password
            $stmt = $db->prepare("UPDATE users SET f_name=?, l_name=?, username=?, email=?, phone=? WHERE u_id=?");
            $stmt->bind_param("sssssi", $firstname, $lastname, $username, $email, $phone, $user_id);
        }
        
        if($stmt->execute()) {
            $success_msg = "Profile updated successfully!";
            // Refresh user data
            $stmt = $db->prepare("SELECT * FROM users WHERE u_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
        } else {
            $error_msg = "Error updating profile. Please try again.";
        }
    }
}
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="images/favicon.png">
    <title>Profile | Food Ordering</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Onest:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #000;
            background-image: url('images/circle-bg.png');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            color: #2d3748;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(231, 235, 244, 0.8);
        }

        .nav-link {
            font-weight: 600;
            padding: 12px 25px !important;
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: #4a5568;
        }

        .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 3px;
            bottom: 0;
            left: 0;
            background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 100%);
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 3px;
        }

        .nav-link:hover {
            color: #6366f1;
        }

        .nav-link:hover:after {
            width: 100%;
        }

        .navbar-brand img {
            height: 45px;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .navbar-brand img:hover {
            transform: scale(1.05);
        }

        .card {
            border: none;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.03);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            margin-top: 40px;
            margin-bottom: 40px;
            border: 1px solid rgba(231, 235, 244, 0.8);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.06);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(231, 235, 244, 0.8);
            padding: 30px;
        }

        .card-header h4 {
            color: #1a202c;
            font-weight: 700;
            margin: 0;
            font-size: 1.75rem;
            background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .form-control {
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            padding: 14px 20px;
            height: auto;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.9);
            font-weight: 500;
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            background: #fff;
        }

        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 8px;
            border-radius: 8px;
        }

        .password-toggle:hover {
            color: #6366f1;
            background: rgba(99, 102, 241, 0.1);
        }

        .theme-btn {
            background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 100%);
            border: none;
            padding: 14px 40px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 20px;
            color: #fff;
            letter-spacing: 0.3px;
        }

        .theme-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
        }

        .alert {
            border-radius: 12px;
            padding: 16px 24px;
            margin-bottom: 25px;
            border: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 10px;
            display: block;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .card-body {
            padding: 40px;
        }

        .validation-message {
            font-size: 0.85rem;
            margin-top: 5px;
            display: none;
            color: #16a34a;
        }

        @media (max-width: 768px) {
            .card {
                margin: 20px;
            }
            
            .card-body {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <?php include("inc/navbar.php"); ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center" style="font-family: 'Onest', sans-serif;">Update Profile</h4>
                    </div>
                    <div class="card-body">
                        <?php if(!empty($success_msg)): ?>
                            <div class="alert alert-success">
                                <i class="fa fa-check-circle"></i>
                                <?php echo $success_msg; ?>
                            </div>
                        <?php endif; ?>
                        <?php if(!empty($error_msg)): ?>
                            <div class="alert alert-danger">
                                <i class="fa fa-exclamation-circle"></i>
                                <?php echo $error_msg; ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="" class="w-100">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>First Name</label>
                                    <input type="text" name="firstname" class="form-control" value="<?php echo htmlspecialchars($user['f_name']); ?>" required oninput="validateFirstName(this)">
                                    <small class="validation-message" id="firstNameMessage"><i class="fa fa-check-circle"></i> Valid first name</small>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Last Name</label>
                                    <input type="text" name="lastname" class="form-control" value="<?php echo htmlspecialchars($user['l_name']); ?>" required oninput="validateLastName(this)">
                                    <small class="validation-message" id="lastNameMessage"><i class="fa fa-check-circle"></i> Valid last name</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required oninput="validateUsername(this)">
                                <small class="validation-message" id="usernameMessage"><i class="fa fa-check-circle"></i> Valid username</small>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required oninput="validateEmail(this)">
                                    <small class="validation-message" id="emailMessage"><i class="fa fa-check-circle"></i> Valid email address</small>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Phone</label>
                                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>" required oninput="validatePhone(this)">
                                    <small class="validation-message" id="phoneMessage"><i class="fa fa-check-circle"></i> Valid phone number</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>New Password</label>
                                    <div class="password-container">
                                        <input type="password" name="new_password" class="form-control" id="newPassword" oninput="validatePassword(this)">
                                        <i class="fa fa-eye password-toggle" onclick="togglePassword('newPassword')"></i>
                                    </div>
                                    <small class="validation-message" id="passwordMessage"><i class="fa fa-check-circle"></i> Valid password</small>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Confirm Password</label>
                                    <div class="password-container">
                                        <input type="password" name="confirm_password" class="form-control" id="confirmPassword" oninput="checkPasswordMatch()">
                                        <i class="fa fa-eye password-toggle" onclick="togglePassword('confirmPassword')"></i>
                                    </div>
                                    <small id="passwordMatchMessage" style="color: #dc2626; display: none;">Passwords do not match</small>
                                    <small class="validation-message" id="confirmPasswordMessage"><i class="fa fa-check-circle"></i> Passwords match</small>
                                </div>
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" class="btn theme-btn">Update Profile</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include "inc/footer.php"; ?>

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

        function validateFirstName(input) {
            const message = document.getElementById('firstNameMessage');
            const isValid = /^[a-zA-Z]+$/.test(input.value);
            message.style.display = isValid ? 'block' : 'none';
        }

        function validateLastName(input) {
            const message = document.getElementById('lastNameMessage');
            const isValid = /^[a-zA-Z]+$/.test(input.value);
            message.style.display = isValid ? 'block' : 'none';
        }

        function validateUsername(input) {
            const message = document.getElementById('usernameMessage');
            const isValid = /^[a-zA-Z0-9_]+$/.test(input.value);
            message.style.display = isValid ? 'block' : 'none';
        }

        function validateEmail(input) {
            const message = document.getElementById('emailMessage');
            const domain = input.value.split('@')[1];
            const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value) && domain === 'vvwusurat.ac.in';
            message.style.display = isValid ? 'block' : 'none';
        }

        function validatePhone(input) {
            const message = document.getElementById('phoneMessage');
            const isValid = /^[0-9]{10}$/.test(input.value);
            message.style.display = isValid ? 'block' : 'none';
        }

        function validatePassword(input) {
            const message = document.getElementById('passwordMessage');
            const isValid = input.value.length >= 6;
            message.style.display = isValid && input.value ? 'block' : 'none';
        }

        function checkPasswordMatch() {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const errorMessage = document.getElementById('passwordMatchMessage');
            const successMessage = document.getElementById('confirmPasswordMessage');

            if (newPassword || confirmPassword) {
                if (newPassword !== confirmPassword) {
                    errorMessage.style.display = 'block';
                    successMessage.style.display = 'none';
                } else {
                    errorMessage.style.display = 'none';
                    successMessage.style.display = 'block';
                }
            } else {
                errorMessage.style.display = 'none';
                successMessage.style.display = 'none';
            }
        }
    </script>
</body>
</html>

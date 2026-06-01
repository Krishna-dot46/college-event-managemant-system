<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if (empty($_SESSION["faculty_id"])) {
    header('location:index.php');
    exit();
}

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $confirmpassword = mysqli_real_escape_string($db, $_POST['confirmpassword']);

    if (!empty($password)) {
        if ($password != $confirmpassword) {
            $error = "Passwords do not match";
        } else if (strlen($password) < 8) {
            $error = "Password must be at least 8 characters long";
        } else {
            $hashed_password = md5($password);
            $sql = "UPDATE faculty SET username=?, email=?, password=? WHERE faculty_id=?";
            $stmt = mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, "sssi", $username, $email, $hashed_password, $_SESSION["faculty_id"]);
            if (mysqli_stmt_execute($stmt)) {
                $success = "Profile updated successfully";
            } else {
                $error = "Failed to update profile";
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        $sql = "UPDATE faculty SET username=?, email=? WHERE faculty_id=?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $username, $email, $_SESSION["faculty_id"]);
        if (mysqli_stmt_execute($stmt)) {
            $success = "Profile updated successfully";
        } else {
            $error = "Failed to update profile";
        }
        mysqli_stmt_close($stmt);
    }
}

$sql = "SELECT * FROM faculty WHERE faculty_id=?";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION["faculty_id"]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$faculty = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Profile Settings</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 16px;
            border: 2px solid #e1e5eb;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-control:focus {
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
            background: #ffffff;
        }

        .btn {
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(66, 153, 225, 0.3);
        }

        .btn-outline-secondary {
            background: #fff;
            border: 2px solid #e1e5eb;
            color: #4a5568;
        }

        .btn-outline-secondary:hover {
            background: #f7fafc;
            border-color: #cbd5e0;
        }

        .card {
            border: none;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            border-radius: 16px;
            margin-bottom: 30px;
            background: #ffffff;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            padding: 24px 32px;
            border: none;
            position: relative;
        }

        .card-header:before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTQ0MCIgaGVpZ2h0PSIxMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZD0iTTAgMGgxNDQwdjEyMEgweiIgZmlsbD0ibm9uZSIvPjxwYXRoIGQ9Ik0wIDBoMTQ0MHYxMjBIMHoiIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iLjA1Ii8+PC9zdmc+') center/cover;
            opacity: 0.1;
        }

        .card-header h4 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 32px;
        }

        .form-group label {
            font-weight: 500;
            color: #2d3748;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 15px 20px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #c6f6d5;
            color: #2f855a;
        }

        .alert-danger {
            background-color: #fed7d7;
            color: #c53030;
        }

        .footer {
            background: #ffffff;
            padding: 20px;
            font-size: 14px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
        }

        .preloader {
            background: rgba(255, 255, 255, 0.98);
        }

        .preloader .circular .path {
            stroke: #4299e1;
        }

        .input-group-append .btn {
            padding: 0 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .input-group {
            align-items: stretch;
        }

        .input-group-append {
            height: auto;
        }

        .form-text {
            color: #718096;
            font-size: 12px;
        }
    </style>
</head>

<body class="fix-header fix-sidebar">
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
        </svg>
    </div>

    <div id="main-wrapper">
        <div class="header">
            <?php include('inc/navbar.php'); ?>
        </div>

        <?php include('inc/sidebar.php'); ?>

        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Profile Settings</h4>
                            </div>
                            <div class="card-body">
                                <?php
                                if (isset($success)) {
                                    echo '<div class="alert alert-success"><i class="fa fa-check-circle mr-2"></i>' . htmlspecialchars($success) . '</div>';
                                }
                                if (isset($error)) {
                                    echo '<div class="alert alert-danger"><i class="fa fa-exclamation-circle mr-2"></i>' . htmlspecialchars($error) . '</div>';
                                }
                                ?>
                                <form method="post" class="needs-validation" novalidate>
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Username</label>
                                                    <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($faculty['username']); ?>" required>
                                                    <div class="invalid-feedback">Please enter a username</div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($faculty['email']); ?>" required>
                                                    <div class="invalid-feedback">Please enter a valid email address</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="mb-2 text-muted">New Password (leave blank to keep current)</label>
                                                    <div class="input-group">
                                                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password" minlength="8">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <small class="form-text text-muted mt-1">Password should be at least 8 characters long</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Confirm New Password</label>
                                                    <div class="input-group">
                                                        <input type="password" name="confirmpassword" id="confirmpassword" class="form-control">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions" style="margin-top: 24px; display: flex; justify-content: center;">
                                        <button type="submit" name="submit" class="btn btn-primary">
                                            <i class="fa fa-check mr-2"></i>Update Profile
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="display: flex; justify-content: center; width: 100%;">
                <?php include("inc/footer.php"); ?>
            </div>
        </div>
    </div>

    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // Password toggle functionality
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
        const confirmpassword = document.querySelector('#confirmpassword');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmpassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmpassword.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        // Password match validation
        confirmpassword.addEventListener('input', function() {
            if (password.value !== this.value) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>

</html>
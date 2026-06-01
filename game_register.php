<!DOCTYPE html>
<html lang="en">
<?php
include("connection/connect.php");
error_reporting(0);
session_start();

if(!isset($_GET['game_id'])) {
    header("location: games.php");
    exit();
}

if(!isset($_SESSION['user_id'])) {
    header("location: login.php");
    exit();
}

$game_id = $_GET['game_id'];
$sql = "SELECT g.*, c.c_name FROM games g JOIN categories c ON g.c_name = c.c_name WHERE g.game_id = ?";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $game_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) == 0) {
    header("location: games.php");
    exit();
}

$game = mysqli_fetch_assoc($result);

// Get logged in user details
$user_id = $_SESSION['user_id'];
$user_sql = "SELECT * FROM users WHERE u_id = ?";
$user_stmt = mysqli_prepare($db, $user_sql);
mysqli_stmt_bind_param($user_stmt, "i", $user_id);
mysqli_stmt_execute($user_stmt);
$user_result = mysqli_stmt_get_result($user_stmt);
$user = mysqli_fetch_assoc($user_result);

// Check if user already registered for this game
$check_sql = "SELECT * FROM game_registration WHERE game_id = ? AND user_id = ?";
$check_stmt = mysqli_prepare($db, $check_sql);
mysqli_stmt_bind_param($check_stmt, "ii", $game_id, $user_id);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);

if(mysqli_num_rows($check_result) > 0) {
    echo "<script>alert('You have already registered for this game!');</script>";
    echo "<script>window.location.href='games.php';</script>";
    exit();
}

// Check total number of games registered
$total_games_sql = "SELECT COUNT(*) as total FROM game_registration WHERE user_id = ?";
$total_games_stmt = mysqli_prepare($db, $total_games_sql);
mysqli_stmt_bind_param($total_games_stmt, "i", $user_id);
mysqli_stmt_execute($total_games_stmt);
$total_games_result = mysqli_stmt_get_result($total_games_stmt);
$total_games = mysqli_fetch_assoc($total_games_result)['total'];

if($total_games >= 2) {
    echo "<script>alert('You can only register for maximum 2 games!');</script>";
    echo "<script>window.location.href='games.php';</script>";
    exit();
}

// Handle form submission
if(isset($_POST['submit'])) {
    $student_name = $user['f_name'] . ' ' . $user['l_name']; 
    $email = $user['email'];
    $contact = $user['phone'];
    $college = mysqli_real_escape_string($db, $_POST['college']);
    $department = mysqli_real_escape_string($db, $_POST['department']); 
    $payment_mode = mysqli_real_escape_string($db, $_POST['payment_mode']);
    
    // Validate inputs
    if(empty($college) || empty($department) || empty($payment_mode)) {
        echo "<script>alert('All fields are required!');</script>";
    } else {
        // Insert into game_registration table
        $insert_sql = "INSERT INTO game_registration (game_id, user_id, student_name, email, contact, college, department, payment_mode) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $insert_stmt = mysqli_prepare($db, $insert_sql);
        mysqli_stmt_bind_param($insert_stmt, "iissssss", $game_id, $user_id, $student_name, $email, $contact, $college, $department, $payment_mode);
        
        if(mysqli_stmt_execute($insert_stmt)) {
            echo "<script>
                alert('Registration completed successfully');
                window.location.href='games.php';
            </script>";
            exit();
        } else {
            echo "<script>alert('Registration failed. Please try again.');</script>";
        }
    }
}
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($game['title']); ?> Registration | Technical Fest 2025</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #000000;
            background-image: url('images/circle-bg.png');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .registration-container {
            max-width: 800px;
            margin: 100px auto 50px;
            padding: 40px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .registration-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .game-details {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 2px solid #eee;
        }

        .game-image {
            width: 180px;
            height: 180px;
            border-radius: 15px;
            object-fit: cover;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .game-image:hover {
            transform: scale(1.05);
        }

        .game-info h2 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: 600;
            font-size: 2rem;
        }

        .game-info p {
            color: #666;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
        }

        .rules-section {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 40px;
            border: 1px solid #e9ecef;
            position: relative;
        }

        .rules-section h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.5rem;
        }

        .rules-content {
            line-height: 1.8;
            color: #4a5568;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 10px;
            display: block;
        }

        .form-control {
            border-radius: 10px;
            padding: 15px;
            border: 2px solid #ddd;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-control[readonly] {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }

        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 16px 35px;
            border-radius: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-size: 1.1rem;
        }

        .btn-register:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .platform-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .platform-tags input[type="radio"] {
            display: none;
        }

        .platform-tag {
            padding: 12px 24px;
            background: #f1f5f9;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .platform-tag i {
            font-size: 1.1rem;
        }

        .platform-tags input[type="radio"]:checked + .platform-tag {
            background: #4299e1;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(66, 153, 225, 0.2);
        }

        @media (max-width: 768px) {
            .registration-container {
                margin: 80px 20px 40px;
                padding: 25px;
            }

            .game-details {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }

            .game-image {
                width: 150px;
                height: 150px;
                margin: 0 auto;
            }

            .game-info h2 {
                font-size: 1.5rem;
            }

            .btn-register {
                padding: 14px 25px;
                font-size: 1rem;
            }

            .rules-section {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <?php include "inc/navbar.php"; ?>

    <div class="registration-container">
        <div class="game-details">
            <img src="admin/games_img/games/<?php echo htmlspecialchars($game['img']); ?>" 
                 alt="<?php echo htmlspecialchars($game['title']); ?>" 
                 class="game-image">
            <div class="game-info">
                <h2><?php echo htmlspecialchars($game['title']); ?></h2>
                <p><i class="fas fa-calendar-alt"></i> Registration Date: <?php echo date('F j, Y', strtotime($game['registration_date'])); ?></p>
                <p><i class="fas fa-tag"></i> Category: <?php echo htmlspecialchars($game['c_name']); ?></p>
                <p><i class="fas fa-rupee-sign"></i> Registration Fee: ₹<?php echo number_format($game['price'], 2); ?></p>
            </div>
        </div>

        <div class="rules-section">
            <h3><i class="fas fa-scroll"></i> Rules & Guidelines</h3>
            <div class="rules-content">
                <?php echo nl2br(htmlspecialchars($game['registration_rules'])); ?>
            </div>
        </div>

        <form method="post" class="registration-form" novalidate>
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Game Name</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($game['title']); ?>" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Game Category</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($game['c_name']); ?>" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Registration Fee</label>
                        <input type="text" class="form-control" value="₹<?php echo number_format($game['price'], 2); ?>" readonly>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="student_name">Student Name</label>
                        <input type="text" class="form-control" id="student_name" name="student_name" value="<?php echo htmlspecialchars($user['f_name'] . ' ' . $user['l_name']); ?>" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="contact">Contact Number</label>
                        <input type="tel" class="form-control" id="contact" name="contact" value="<?php echo htmlspecialchars($user['phone']); ?>" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="college">College Name</label>
                        <input type="text" class="form-control" id="college" name="college" required placeholder="Enter your college name">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="department">Department Name</label>
                        <input type="text" class="form-control" id="department" name="department" required placeholder="Enter your department name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Mode of Payment</label>
                        <div class="platform-tags">
                            <input type="radio" id="cash" name="payment_mode" value="Cash" required>
                            <label for="cash" class="platform-tag">
                                <i class="fas fa-money-bill-wave"></i> Cash
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" name="submit" class="btn btn-register">
                <i class="fas fa-check-circle"></i> Confirm Registration
            </button>
        </form>
    </div>

    <?php include "inc/footer.php"; ?>

    <script src="inc/script.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation with enhanced UX
        document.querySelector('.registration-form').addEventListener('submit', function(e) {
            const college = document.getElementById('college').value.trim();
            const department = document.getElementById('department').value.trim();
            const paymentMode = document.querySelector('input[name="payment_mode"]:checked');
            
            if(!college || !department || !paymentMode) {
                e.preventDefault();
                Swal.fire({
                    title: 'Error!',
                    text: 'Please fill in all required fields',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }
        });

        // Enhance form field interactions
        const formFields = document.querySelectorAll('.form-control:not([readonly])');
        formFields.forEach(field => {
            field.addEventListener('focus', function() {
                this.style.transform = 'scale(1.02)';
            });
            
            field.addEventListener('blur', function() {
                this.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>

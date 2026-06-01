<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();
if (empty($_SESSION["adm_id"])) {
    header('location:index.php');
} else {
?>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Admin Dashboard</title>
        <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
        <link href="css/helper.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f4f6f9;
            }

            .card {
                background: white;
                border-radius: 15px;
                overflow: hidden;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
                margin-bottom: 30px;
                transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
                border: none;
                animation: fadeIn 0.8s ease-out;
                opacity: 0;
                animation-fill-mode: forwards;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .card:hover {
                transform: translateY(-5px) scale(1.02);
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
            }

            .card-header {
                background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
                border-radius: 15px 15px 0 0;
                padding: 25px;
                position: relative;
                overflow: hidden;
                border-bottom: none;
            }

            .card-header h4 {
                margin: 0;
                font-size: 1.5rem;
                font-weight: 600;
                letter-spacing: 0.5px;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
                color: #fff;
            }

            .card-body {
                padding: 30px;
                background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            }

            .f-s-40 {
                font-size: 40px;
                transition: all 0.3s ease;
            }

            .text-primary { color: #6366f1 !important; }
            .text-success { color: #10b981 !important; }
            .text-info { color: #0ea5e9 !important; }
            .text-warning { color: #f59e0b !important; }

            .d-flex {
                display: flex !important;
                align-items: center !important;
            }

            .mr-3 {
                margin-right: 2rem !important;
            }

            h2.mb-0 {
                font-size: 2.25rem;
                font-weight: 700;
                color: #1e293b;
                margin-bottom: 0;
            }

            .text-muted {
                color: #64748b !important;
                font-size: 1rem;
                font-weight: 500;
                letter-spacing: 0.5px;
            }

            .page-wrapper {
                background: #f4f6f9;
                padding-bottom: 60px;
            }

            @media (max-width: 768px) {
                .card-body {
                    padding: 20px;
                }

                h2.mb-0 {
                    font-size: 1.75rem;
                }

                .f-s-40 {
                    font-size: 32px;
                }

                .text-muted {
                    font-size: 0.875rem;
                }
            }
        </style>
    </head>

    <body class="fix-header fix-sidebar">
        <div class="preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
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
                                    <h4 class="m-b-0">Admin Dashboard</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="mr-3">
                                                            <span class="text-primary"><i class="fa fa-users f-s-40"></i></span>
                                                        </div>
                                                        <div>
                                                            <h2 class="mb-0"><?php
                                                                $sql = "select * from users";
                                                                $result = mysqli_query($db, $sql);
                                                                echo mysqli_num_rows($result);
                                                            ?></h2>
                                                            <p class="text-muted mb-0">Total Users</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="mr-3">
                                                            <span class="text-success"><i class="fa fa-gamepad f-s-40"></i></span>
                                                        </div>
                                                        <div>
                                                            <h2 class="mb-0"><?php
                                                                $sql = "select * from games";
                                                                $result = mysqli_query($db, $sql);
                                                                echo mysqli_num_rows($result);
                                                            ?></h2>
                                                            <p class="text-muted mb-0">Total Games</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="mr-3">
                                                            <span class="text-info"><i class="fa fa-graduation-cap f-s-40"></i></span>
                                                        </div>
                                                        <div>
                                                            <h2 class="mb-0"><?php
                                                                $sql = "select * from faculty";
                                                                $result = mysqli_query($db, $sql);
                                                                echo mysqli_num_rows($result);
                                                            ?></h2>
                                                            <p class="text-muted mb-0">Total Faculty</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="mr-3">
                                                            <span class="text-warning"><i class="fa fa-clipboard f-s-40"></i></span>
                                                        </div>
                                                        <div>
                                                            <h2 class="mb-0"><?php
                                                                $sql = "select * from game_registration";
                                                                $result = mysqli_query($db, $sql);
                                                                echo mysqli_num_rows($result);
                                                            ?></h2>
                                                            <p class="text-muted mb-0">Game Registrations</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: center; width: 100%;">
                <?php include("inc/footer.php"); ?>
            </div>
        </div>

        <script src="js/lib/jquery/jquery.min.js"></script>
        <script src="js/lib/bootstrap/js/popper.min.js"></script>
        <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
        <script src="js/jquery.slimscroll.js"></script>
        <script src="js/sidebarmenu.js"></script>
        <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
        <script src="js/custom.min.js"></script>
    </body>

</html>
<?php
}
?>
<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

// Check if admin is logged in
if(!isset($_SESSION['adm_id'])) {
    header("location: login.php");
    exit();
}

// Get all registrations with user and game details
$sql = "SELECT gr.*, g.title as game_title, g.price, u.username, u.f_name, u.l_name, u.email, u.phone 
        FROM game_registration gr
        JOIN games g ON gr.game_id = g.game_id
        JOIN users u ON gr.user_id = u.u_id
        ORDER BY gr.registration_date DESC";
$result = mysqli_query($db, $sql);
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Admin Dashboard - All Game Registrations">
    <title>Admin - All Game Registrations</title>
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
        }
        .table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .table thead th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            text-transform: uppercase;
            padding: 15px;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #dee2e6;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
            transition: all 0.3s ease;
        }

        .btn {
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
        }

        .btn-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            border: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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

        .card-header h4 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>All Game Registrations</h4>
                                </div>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Sr No.</th>
                                                <th>Game</th>
                                                <th>User Details</th>
                                                <th>Contact Info</th>
                                                <th>Registration Date</th>
                                                <th>Status</th>
                                                <th>Amount</th>
                                                <th style="text-align: center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if(mysqli_num_rows($result) > 0) {
                                                while($row = mysqli_fetch_assoc($result)) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $row['registration_id']; ?></td>
                                                        <td><?php echo $row['game_title']; ?></td>
                                                        <td>
                                                            <?php echo $row['f_name'] . ' ' . $row['l_name']; ?><br>
                                                            <small>(@<?php echo $row['username']; ?>)</small>
                                                        </td>
                                                        <td>
                                                            <?php echo $row['email']; ?><br>
                                                            <?php echo $row['phone']; ?>
                                                        </td>
                                                        <td><?php echo date('d M Y H:i', strtotime($row['registration_date'])); ?></td>
                                                        <td>
                                                            <span class="status-badge <?php 
                                                                $status = $row['registration_status'];
                                                                $statusClass = '';
                                                                $bgColor = '';
                                                                $icon = '';
                                                                
                                                                switch($status) {
                                                                    case 'approved':
                                                                        $statusClass = 'success';
                                                                        $bgColor = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';
                                                                        $icon = 'fa-check-circle';
                                                                        break;
                                                                    case 'rejected':
                                                                        $statusClass = 'danger'; 
                                                                        $bgColor = 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)';
                                                                        $icon = 'fa-times-circle';
                                                                        break;
                                                                    default:
                                                                        $statusClass = 'warning';
                                                                        $bgColor = 'linear-gradient(135deg, #ffc107 0%, #fd7e14 100%)';
                                                                        $icon = 'fa-clock';
                                                                }
                                                                echo $statusClass;
                                                            ?>" style="
                                                                display: inline-flex;
                                                                align-items: center;
                                                                padding: 8px 16px;
                                                                border-radius: 50px;
                                                                font-size: 14px;
                                                                font-weight: 600;
                                                                text-transform: uppercase;
                                                                letter-spacing: 0.5px;
                                                                background: <?php echo $bgColor; ?>;
                                                                color: #fff;
                                                                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                                                                border: none;
                                                                transition: all 0.3s ease;
                                                                gap: 6px;">
                                                                <i class="fa <?php echo $icon; ?>"></i>
                                                                <?php echo ucfirst($status); ?>
                                                            </span>
                                                        </td>
                                                        <td>₹<?php echo $row['price']; ?></td>
                                                        <td style="white-space: nowrap; text-align: center;">
                                                            <div class="btn-group" role="group" style="display: inline-flex; gap: 5px;">
                                                                <button class="btn btn-info btn-flat btn-addon btn-sm" onclick="viewDetails(<?php echo $row['registration_id']; ?>)" style="min-width: 90px;">
                                                                    <i class="fa fa-eye"></i> View
                                                                </button>
                                                                <button class="btn btn-danger btn-flat btn-addon btn-sm" onclick="deleteRegistration(<?php echo $row['registration_id']; ?>)" style="min-width: 90px;">
                                                                    <i class="fa fa-trash-o"></i> Delete
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="8" class="text-center">No registrations found</td>
                                                </tr>
                                                <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
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
        $(document).ready(function() {
            // Initialize DataTable with custom options
            var table = $('#myTable').DataTable({
                "order": [[0, "desc"]], 
                "pageLength": 10,
                "language": {
                    "search": "Search registrations:",
                    "lengthMenu": "Show _MENU_ entries per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ registrations",
                    "infoEmpty": "No registrations available",
                    "infoFiltered": "(filtered from _MAX_ total registrations)"
                },
                "dom": '<"top"lf>rt<"bottom"ip><"clear">',
                "responsive": true
            });
        });

        function viewDetails(registrationId) {
            window.location.href = 'registration_details.php?id=' + registrationId;
        }

        function deleteRegistration(registrationId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this registration!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'delete_registration.php?id=' + registrationId;
                }
            });
        }
    </script>
</body>
</html>

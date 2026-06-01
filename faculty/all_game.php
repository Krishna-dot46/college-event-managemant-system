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

?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>All Games</title>
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

        .img-fluid {
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
                                    <h4>All Games</h4>
                                </div>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr style="text-align: center;">
                                                <th>No</th>
                                                <th>Game</th>
                                                <th>Description</th>
                                                <th>Price</th>
                                                <th>Category</th>
                                                <th>Registration Date</th>
                                                <th>Image</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Fetch all games ordered by newest first
                                            $sql = "SELECT * FROM games ORDER BY game_id DESC";
                                            $query = mysqli_query($db, $sql);

                                            if (!$query) {
                                                die("Query failed: " . mysqli_error($db));
                                            }

                                            // Display games
                                            if (mysqli_num_rows($query) == 0) {
                                                echo '<tr><td colspan="8"><center>No Games Found</center></td></tr>';
                                            } else {
                                                $total_rows = mysqli_num_rows($query);
                                                $sr_no = $total_rows;
                                                while ($rows = mysqli_fetch_array($query)) {
                                                    $price = number_format($rows['price'], 2);
                                                    echo '<tr>
                                                        <td>' . $sr_no . '</td>
                                                        <td>' . htmlspecialchars($rows['title']) . '</td>
                                                        <td>' . htmlspecialchars($rows['description']) . '</td>
                                                        <td>₹' . htmlspecialchars($price) . '</td>
                                                        <td>' . htmlspecialchars($rows['c_name']) . '</td>
                                                        <td>' . htmlspecialchars($rows['registration_date']) . '</td>
                                                        <td>';
                                                    // Display game image if available
                                                    if (!empty($rows['img']) && file_exists("../admin/games_img/games/" . $rows['img'])) {
                                                        echo '<img src="../admin/games_img/games/' . htmlspecialchars($rows['img']) . '" class="img-fluid" style="max-height:100px;" alt="' . htmlspecialchars($rows['title']) . '">';
                                                    } else {
                                                        echo 'No image available';
                                                    }
                                                    echo '</td>';
                                                    echo '<td style="white-space: nowrap; text-align: center;">
                                                        <div class="btn-group" role="group" style="display: inline-flex; gap: 5px;">
                                                            <button class="btn btn-info btn-flat btn-addon btn-sm" onclick="viewDetails(' . $rows['game_id'] . ')" style="min-width: 90px;">
                                                                <i class="fa fa-eye"></i> View
                                                            </button>
                                                        </div>
                                                    </td></tr>';
                                                    $sr_no--;
                                                }
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

    <!-- Scripts -->
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
                "order": [
                    [0, "desc"]
                ], // Order by first column (Sr No) descending
                "pageLength": 10,
                "language": {
                    "search": "Search games:",
                    "lengthMenu": "Show _MENU_ entries per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ games",
                    "infoEmpty": "No games available",
                    "infoFiltered": "(filtered from _MAX_ total games)"
                },
                "dom": '<"top"lf>rt<"bottom"ip><"clear">',
                "responsive": true
            });
        });

        function viewDetails(gameId) {
            window.location.href = 'game_details.php?id=' + gameId;
        }
    </script>
</body>

</html>
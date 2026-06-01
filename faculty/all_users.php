<!DOCTYPE html>
<html lang="en">
<?php
include("../connection/connect.php");
error_reporting(0);
session_start();

if (empty($_SESSION["faculty_id"])) {
    header('location:index.php');
}
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>All Users</title>
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
            border-radius: 4px;
            padding: 8px 16px;
            margin: 2px;
            font-weight: 500;
        }

        .btn-danger {
            background: #dc3545;
            border: none;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .dataTables_wrapper {
            padding: 20px;
        }

        .dataTables_filter input {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 6px 12px;
        }

        .dataTables_length select {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 6px 12px;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>

<body class="fix-header fix-sidebar" style="background-color: #fafafa;">
    <div class="preloader" style="background-color: rgba(255,255,255,0.9); width: 100%; height: 100%; top: 0; position: fixed; z-index: 99999;">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>
    <div id="main-wrapper">
        <div class="header">
            <?php include('inc/navbar.php'); ?>
        </div>

        <?php include('inc/sidebar.php'); ?>

        <div class="page-wrapper" style="background: #fafafa; padding-bottom: 60px;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="col-lg-12">
                            <div class="card" style="box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-radius: 8px; margin-bottom: 30px;">
                                <div class="card-header" style="background: linear-gradient(45deg, #007bff, #0056b3); border-radius: 8px 8px 0 0; padding: 20px; position: relative; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                    <h4 class="m-b-0 text-white" style="margin: 0; font-size: 1.5rem; font-weight: 600; letter-spacing: 0.5px; text-shadow: 1px 1px 2px rgba(0,0,0,0.1); position: relative; z-index: 1;">All Users</h4>
                                </div>
                                <div class="table-responsive m-t-40">
                                    <table id="myTable" class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr style="text-align: center;">
                                                <th>Username</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Address</th>
                                                <th>Reg-Date</th>
                                                <th style="text-align: center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM users ORDER BY u_id DESC";
                                            $query = mysqli_query($db, $sql);

                                            if (!mysqli_num_rows($query) > 0) {
                                                echo '<tr><td colspan="8"><center>No Users</center></td></tr>';
                                            } else {
                                                while ($rows = mysqli_fetch_array($query)) {
                                                    echo '<tr>
                                                        <td>' . htmlspecialchars($rows['username']) . '</td>
                                                        <td>' . htmlspecialchars($rows['f_name']) . '</td>
                                                        <td>' . htmlspecialchars($rows['l_name']) . '</td>
                                                        <td>' . htmlspecialchars($rows['email']) . '</td>
                                                        <td>' . htmlspecialchars($rows['phone']) . '</td>
                                                        <td>' . htmlspecialchars($rows['address']) . '</td>
                                                        <td>' . htmlspecialchars($rows['date']) . '</td>
                                                        <td style="white-space: nowrap; text-align: center;">
                                                            <button class="btn btn-danger btn-flat btn-addon btn-sm delete-user" style="min-width: 90px;" data-id="' . htmlspecialchars($rows['u_id']) . '">
                                                                <i class="fa fa-trash-o"></i> Delete
                                                            </button>
                                                        </td>
                                                    </tr>';
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
                "order": [[ 6, "desc" ]],
                "pageLength": 10,
                "language": {
                    "search": "Search users:",
                    "lengthMenu": "Show _MENU_ entries per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ users",
                    "infoEmpty": "No users available",
                    "infoFiltered": "(filtered from _MAX_ total users)"
                },
                "dom": '<"top"lf>rt<"bottom"ip><"clear">',
                "responsive": true
            });

            $('.delete-user').click(function() {
                const userId = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this user account!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'delete_users.php',
                            type: 'POST',
                            data: {
                                user_del: userId
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'User account has been deleted successfully.',
                                    icon: 'success',
                                    timer: 1000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Failed to delete user account.',
                                    icon: 'error',
                                    confirmButtonColor: '#3085d6'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>
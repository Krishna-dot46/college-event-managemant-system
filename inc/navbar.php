<header id="header" class="header-scroll top-header headrom" style="position: relative;">
    <div class="university-banner" style="background: #fff; padding: 15px 0; border-bottom: 1px solid rgba(0,0,0,0.1);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    
                    <img src="images/vvwu.webp" alt="University Logo" style="height: 80px; width: 80px;">
                </div>
                <div class="col-md-8 text-center">
                    <h1 style="color: #764ba2; font-size: 2rem; font-weight: 700; margin: 0; font-family: 'Onest', sans-serif; text-align: center; letter-spacing: 1px;">
                        VANITA VISHRAM WOMEN'S UNIVERSITY
                    </h1>
                    <p style="color: #667eea; font-size: 1rem; margin: 5px 0; text-align: center;">
                        (Managed By: Vanita Vishram, Surat)
                    </p>
                    <p style="color: #764ba2; font-size: 1.1rem; font-weight: 600; text-align: center;">
                        1st Women's University of Gujarat
                    </p>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-dark" style="background: rgba(255, 255, 255, 0.95); box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); backdrop-filter: blur(5px);">
        <div class="container">
            <img src="images/vbytelogo.webp" alt="Traditional Emblem" style="height: 45px; width: 45px;">
            <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
            <div class="collapse navbar-toggleable-md float-lg-right" id="mainNavbarCollapse">
                <ul class="nav navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php" style="color: #000; font-weight: 500; padding: 12px 25px !important; position: relative; transition: all 0.3s ease;">Home <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="about.php" style="color: #000; font-weight: 500; padding: 12px 25px !important; position: relative; transition: all 0.3s ease;">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="gallery.php" style="color: #000; font-weight: 500; padding: 12px 25px !important; position: relative; transition: all 0.3s ease;">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="games.php" style="color: #000; font-weight: 500; padding: 12px 25px !important; position: relative; transition: all 0.3s ease;">Games</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="schedule.php" style="color: #000; font-weight: 500; padding: 12px 25px !important; position: relative; transition: all 0.3s ease;">Schedule</a>
                    </li>
                    <?php
                    if (isset($_SESSION['user_id'])) {
                        echo '<li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle active" href="#" id="userDropdown" role="button" style="color: #000; font-weight: 500; padding: 12px 25px !important; position: relative; transition: all 0.3s ease;">
                                    <i class="fa fa-user"></i> Account
                                </a>
                                <div class="dropdown-menu" style="background: rgba(33, 33, 33, 0.95); border: none; border-radius: 8px; margin-top: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                                    <a class="dropdown-item" href="my_registrations.php" style="color: white; padding: 10px 25px; transition: all 0.3s ease;">My Registrations</a>
                                    <a class="dropdown-item" href="result.php" style="color: white; padding: 10px 25px; transition: all 0.3s ease;">Result</a>
                                    <a class="dropdown-item" href="winner.php" style="color: white; padding: 10px 25px; transition: all 0.3s ease;">Winner List</a>
                                    <a class="dropdown-item" href="profile.php" style="color: white; padding: 10px 25px; transition: all 0.3s ease;">Profile</a>
                                    <a class="dropdown-item" href="logout.php" style="color: white; padding: 10px 25px; transition: all 0.3s ease;"><i class="fa fa-sign-out"></i> Logout</a>
                                </div>
                              </li>';
                    } else {
                        echo '<li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle active" href="#" id="loginDropdown" role="button" style="color: #000; font-weight: 500; padding: 12px 25px !important; position: relative; transition: all 0.3s ease;">
                                    Login
                                </a>
                                <div class="dropdown-menu" style="background: rgba(33, 33, 33, 0.95); border: none; border-radius: 8px; margin-top: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                                    <a class="dropdown-item" href="admin/index.php" style="color: white; padding: 10px 25px; transition: all 0.3s ease;">Admin Login</a>
                                    <a class="dropdown-item" href="faculty/index.php" style="color: white; padding: 10px 25px; transition: all 0.3s ease;">Faculty Login</a>
                                    <a class="dropdown-item" href="registration.php" style="color: white; padding: 10px 25px; transition: all 0.3s ease;">Join Now</a>
                                </div>
                              </li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    <style>
        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.1) !important;
        }

        .dropdown-menu {
            display: none;
        }

        .dropdown-menu.show {
            display: block;
        }
    </style>
</header>
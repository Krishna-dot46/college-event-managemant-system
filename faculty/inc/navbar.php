<nav class="navbar top-navbar navbar-expand-md navbar-light">

    <div class="navbar-header">
    </div>

    <div class="navbar-collapse">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <span class="nav-link text-muted">
                    <?php 
                    date_default_timezone_set('Asia/Kolkata');
                    echo date('l, j  F, Y');
                    ?>
                </span>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-muted" href="profile.php" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="images/bookingSystem/user-icn.png" alt="user" class="profile-pic" />
                </a>
            </li>
        </ul>
    </div>
</nav>
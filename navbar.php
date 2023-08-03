<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="home.php">ISOC Member Events </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav ml-auto">
      <?php if (isset($_SESSION["email"])) { // Check if the user is logged in ?>
        <li class="nav-item active">
          <a class="nav-link" href="">Logged in as <?php echo $_SESSION["email"]; ?></a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="home.php">Home</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="user_attendance.php">Attendance Events</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="winner.php">Result</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
      <?php } else {  ?>
        <li class="nav-item">
          <a class="nav-link" href="register.php">Register</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="login.php">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./admin/admin_login.php">Login as Admin</a>
        </li>
      <?php } ?>
    </ul>
  </div>
</nav>

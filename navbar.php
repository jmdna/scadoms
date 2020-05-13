<!DOCTYPE html>
<html lang="en">
<head>
<link href="bootstrap-4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php session_start();

$maintenance =$tickets='';
if (preg_match("/DC Head/",$_SESSION['user_level'])){
  $maintenance = ' <li class="nav-item dropdown" >
            <a class="nav-link dropdown-toggle "  id="navbarDropdown" role="button" data-toggle="dropdown" >Maintenance</a>
            <div class="dropdown-menu " aria-labelledby="navbarDropdown">
                <a class="dropdown-item <?php if($page==\'maintenance\'){echo \'active\';} ?>" href="companies.php">Company Profile</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="projects.php">Project Profile</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="register.php">Users</a> 
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="request_type.php">Type of Request</a>
            </div>
          </li>';
  $tickets = '<div class="dropdown-menu " aria-labelledby="navbarDropdown">
                
                <a class="dropdown-item" href="view_ticket.php">View Ticket</a>
                <!-- <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Approve Ticket</a>  -->
            </div>';

}
if (preg_match("/Approver/",$_SESSION['user_level']) || preg_match("/Assistant/",$_SESSION['user_level'])) {
  $maintenance = '';
  $tickets = '<div class="dropdown-menu " aria-labelledby="navbarDropdown">
                
                <a class="dropdown-item" href="view_ticket.php">View Ticket</a>
                <!-- <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Approve Ticket</a>  -->
            </div>';
}
if (preg_match("/Employee/",$_SESSION['user_level']) ) {
  $maintenance = '';
  $tickets = '<div class="dropdown-menu " aria-labelledby="navbarDropdown">
  <a class="dropdown-item" href="create_ticket.php">Create Ticket</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="view_ticket.php">View Ticket</a>
                
            </div>';
}
?>

<nav class = "navbar sticky-top navbar-expand-md navbar-dark "   style="background-color: #00146F;>
    <a class="navbar-brand href=""><img class= "img-thumbnail" src="img/logo.png"  height="110" width="110"></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
      </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item <?php if($page=='home'){echo 'active';} ?> ">
          <a class="nav-link" href="home.php">Home</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle <?php if($page=='tickets'){echo 'active';} ?> "  id="navbarDropdown" role="button" data-toggle="dropdown">Tickets</a>
            <?php echo $tickets; ?>
          </li>
          <li class="nav-item <?php if($page=='reports'){echo 'active';} ?>" >
          <a class="nav-link" href="export.php" >Reports </a>
          </li>
          <?php echo $maintenance; ?>
          <li class="nav-item dropdown ">
            <a class="nav-link dropdown-toggle  <?php if($page=='account'){echo 'active';} ?> "  id="navbarDropdown" role="button" data-toggle="dropdown"><?php  echo $_SESSION['firstname'].'\'s '; ?>Account</a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item"href="change_password.php">Change Password</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="index.php" >Logout</a>
            </div>
          </li>
        </ul>
      </div>
 
  </nav>

    <!-- Bootstrap core JavaScript -->
    <script src="bootstrap-4.3.1/js/popper.min.js"></script>
  <script src="bootstrap-4.3.1/js/bootstrap.min.js" ></script>
  <script src="bootstrap-4.3.1/vendor/jquery/jquery.slim.min.js"></script>  
  <script src="bootstrap-4.3.1/js/bootstrap.bundle.min.js" ></script>
  </body>
</html>
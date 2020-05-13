 <!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1.0">
 	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  	<link rel="stylesheet" href="bootstrap-4.3.1/css/bootstrap.min.css" >
  	<title>SCADOMS - Change Password</title>
    <script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function(){
        $("#errModal").modal('show');
        });
      </script>
</head>
<body>
<?php
$page='account';
include('navbar.php');
$un = $_SESSION['username'];
$old_password=$new_password=$confirm_password='';
$old_passwordErr = $new_passwordErr=$confirm_passwordErr='';
$result = "";
include('connection.php');

if (isset($_POST["btnSave"])){
      if(empty($_POST["old_password"])){
    		$old_passwordErr = "Old password required";
    	 }else{
    		$old_password=$_POST["old_password"];

      if(empty($_POST["new_password"])){
        $new_passwordErr = "New password required";
      }else{
        $new_password=$_POST["new_password"];

      if(empty($_POST["confirm_password"])){
        $confirm_passwordErr = "Confirm password required";
      }else{
        $confirm_password=$_POST["confirm_password"];

      if($_POST["new_password"] == $_POST["confirm_password"]){
        $new_password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_password"];
          $connect = mysqli_connect("127.0.0.1", "root", "admin", "scadoms"); 
          $query = "Select * from users where id = ". $_SESSION['id'] ." and password='".$old_password."'";
          $result = mysqli_query($connect,$query);
          if (mysqli_num_rows($result)>0){
            $link = mysqli_connect($hostname, $username, $password, $dbname);
            if($link === false){ die("ERROR: Could not connect. " . mysqli_connect_error());}
            $sql = "update users set password ='". $new_password. "' where id=".$_SESSION['id'];
            if(mysqli_query($link, $sql)){
              echo '<div class="modal fade bd-example-modal-lg" tabindex="-1" id ="errModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                          <div class="modal-body">
                            <h3 center>Password Successfully Changed! </h3>
                          </div>
                        <div class="modal-footer">
                          <button  type="button" class="btn btn-secondary" onclick="window.location=\'index.php\'" data-dismiss="modal">close</button>
                          <?php header("refresh:0,index.php");?>
                        </div>
                    </div>
                    </div>
        </div>';        
            }

      mysqli_close($link); 
  }else{
    echo '<div class="modal fade bd-example-modal-lg" tabindex="-1" id ="errModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
              <div class="modal-body" >
                    <h3 center>Invalid old password. </h3>
              </div>
              <div class="modal-footer">
          <button  type="button" class="btn btn-secondary" onclick="window.location=\'change_password.php\'" data-dismiss="modal">close</button>
        </div>
              </div>
          </div>
        </div>';
  }

}else{
  $new_passwordErr="Password do not match";
  $confirm_passwordErr = "Password do not match";
}


  
  

}}}}
?>
<style>
	.error{color:Red;}
</style>
<section id="cover" class="min-vh-100">
    <div id="cover-caption">
        <div class="container">
            <div class="row ">
                <div class="col-xl-5 col-lg-6 col-md-8 col-sm-10 mx-auto text-center form p-4">
                    <h1 class="display-4 py-2 text-truncate">Change Password</h1>
                    <div class="px-2">
                        <form method="POST" class="justify-content-center" >
                            <div class="form-group ">
                                <input type="text"  name="username" id="username" class="form-control " disabled  value="<?php echo $un; ?>">
                            </div>
                            <div class="form-group ">
                                <input type="password" name="old_password" id="old_password" class="form-control"  placeholder="Old Password" value="<?php echo $old_password; ?>"><span class="error"><?php echo $old_passwordErr; ?></span>
                            </div>
                            <div class="form-group ">
                                <input type="password" name="new_password" id="new_password" class="form-control"  placeholder="New Password" value="<?php echo $new_password; ?>"><span class="error"><?php echo $new_passwordErr; ?></span>
                            </div>  
                            <div class="form-group ">
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control"  placeholder="Confirm Password" value="<?php echo $confirm_password; ?>"><span class="error"><?php echo $confirm_passwordErr; ?></span>
                            </div>
                            
                            <button type="submit" name= "btnSave" class="btn btn-info btn-block">Save</button>
                            <input class = "btn btn-secondary btn-block" type="button" name="cancel" value="cancel" onClick="window.location='home.php';" />
</form>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</form>

  <script src="bootstrap-4.3.1/js/bootstrap.min.js" ></script>
  <script src="bootstrap-4.3.1/vendor/jquery/jquery.slim.min.js"></script>
  <script src="bootstrap-4.3.1/js/popper.min.js"></script>
  <script src="bootstrap-4.3.1/js/bootstrap.bundle.min.js" ></script>
  	
</html>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1.0">
 	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  	<link rel="stylesheet" href="bootstrap-4.3.1/css/bootstrap.min.css" >
  	<title>Log In</title>
</head>
<body>
<?php
session_start(); 
 
$host='127.0.0.1';
$user='root';
$pass='admin';
$db='scadoms';
$tbl= 'users';
$fn = 'first_name';
$result='';

$username = $password ="";
$usernameErr = $passwordErr ="";

if (isset($_POST["btnLogin"])){
	
	if(empty($_POST["username"])){
		$usernameErr = "Username required";
	}else{
		$username=$_POST["username"];
	}

	if(empty($_POST["password"])){
		$passwordErr = "Password is required";
	}else{
		$password=$_POST["password"];
    
	}
  if (($username) and ($password)){
    
    $con = mysqli_connect($host, $user, $pass);
    mysqli_connect($host, $user, $pass) OR die("Unable to connect.");
    mysqli_select_db($con,$db);
    $query = "select * from $tbl where username='$username' and password='$password'";
    $result = mysqli_query($con,$query);
    $cnt = mysqli_num_rows(mysqli_query($con,$query));

    if ($cnt==1) {
      
      while ($row = mysqli_fetch_array($result)){
        
        $_SESSION['firstname']=$row ["$fn"];
        $_SESSION['email_address']= $row['email_address'];
        $_SESSION['id']= $row['id'];
        $_SESSION['user_level']=$row['user_level'];
         $_SESSION['username']=$row ['username'];
        header("location:home.php");    
      }
        
    }else{
        $message = "Username and/or Password incorrect.\\nTry again.";
      echo "<script type='text/javascript'>alert('$message');</script>";
      header("Refresh:0");
    }
}
}
?>
<style>
	.error{color:Red;}
</style>
<section id="cover" class="min-vh-100 ">
    <div id="cover-caption">
        <div class="container ">
            <div class="row ">
                <div class="col-xl-6 col-lg-6 col-md-8 col-sm-10 mx-auto  text-center form p-4">
                    
                    <div class="px-2">
                        <form method="POST" class="justify-content-center" >
                        	<div class="col-xl-8 col-lg-6 col-md-8 col-sm-10 mx-auto text-center form p-4 ">
                        		<img src="img\logo.png" class="img-fluid" alt="..."><br>

                            </div>
                            <h6 class="display-5 py-1 ">CONTRACT ADJUSTMENT AND ONLINE MONITORING SYSTEM</h6>
                            <div class="form-group has-error has-feedback">
                                <input type="text" name="username" id="username" class="form-control"  placeholder="Username" value="<?php echo $username; ?>"><span class="error"><?php echo $usernameErr; ?></span>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" id="password" class="form-control"  placeholder="Password" value = "<?php echo $password; ?>"><span class="error"><?php echo $passwordErr; ?></span>
                            </div>
                            
                            <button type="submit" name= "btnLogin" class="btn btn-info btn-block">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</form>

	<script src="bootstrap-4.3.1/js/bootstrap.bundle.js" ></script>
  	<script src="bootstrap-4.3.1/js/bootstrap.js" ></script>
  	<script src="bootstrap-4.3.1/js/bootstrap.min.js" ></script>
  	<script src="bootstrap-4.3.1/js/bootstrap.bundle.min.js" ></script>
  	
</body>
</html>
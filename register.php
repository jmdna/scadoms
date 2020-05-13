 <!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1.0">
 	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  	<link rel="stylesheet" href="bootstrap-4.3.1/css/bootstrap.min.css" >
    <link href="bootstrap-4.3.1/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  	<title>SCADOMS - Users Registration</title>
</head>
<body>
<?php
include('navbar.php');
if (!preg_match("/DC Head/",$_SESSION['user_level'])){ header("Refresh:0,home.php");}
$page='maintenance';

$hostname = "127.0.0.1";
$user = "root";
$dbname = "scadoms";
$s_password = "";
$companies_table = "companies";
$column_name = "company_name";
$column_code = "company_code";
$con = mysqli_connect($hostname, $user, $s_password);
mysqli_connect($hostname, $user, $s_password) OR die("Unable to connect.");
mysqli_select_db($con,$dbname);
$query = "select * from $companies_table";
$result = mysqli_query($con,$query);

$first_name = $middle_name = $last_name = $user_unit = $gender = $user_level = $username = $password = $password_confirm = $email= $company="";
$first_nameErr = $middle_nameErr = $last_nameErr = $user_unit = $genderErr = $userErr = $usernameErr = $passwordErr = $password_confirmErr = $emailErr=$companyErr="";

if (isset($_POST["btnRegister"])){
	
	if(empty($_POST["first_name"])){
		$first_nameErr = "First name is required";
	}else{
		$first_name=$_POST["first_name"];
	}

	if(empty($_POST["middle_name"])){
		$middle_nameErr = "Middle name is required";
	}else{
		$middle_name=$_POST["middle_name"];
	}

	if(empty($_POST["last_name"])){
		$last_nameErr = "Last name is required";
	}else{
		$last_name=$_POST["last_name"];
	}
	if(empty($_POST["user_unit"])){
		$user_unitErr = "User unit is required";
	}else{
		$user_unit=$_POST["user_unit"];
	}

	if(empty($_POST["gender"])){
		$genderErr = "Gender is required";
	}else{
		$gender=$_POST["gender"];
	}

	if(empty($_POST["user_level"])){
		$user_levelErr = "User level is required";
	}else{
		$user_level=$_POST["user_level"];
	}
	if(empty($_POST["email"])){
		$emailErr = "Email is required";
	}else{
		$email=$_POST["email"];
	}

	if(empty($_POST["username"])){
		$usernameErr = "Username is required";
	}else{
		$username=$_POST["username"];
	}

	if(empty($_POST["password"])){
		$passwordErr = "Password is required";
	}else{
		$password=$_POST["password"];
	}

	if(empty($_POST["password_confirm"])){
		$passwordErr = "Please confirm password";
	}else{
		$password=$_POST["password_confirm"];
	}
	if($_POST["password"] == $_POST["password_confirm"]){
		$password = $_POST["password"];
		$password_confirm = $_POST["password_confirm"];
	}else{
		$passwordErr="Password do not match";
		$password_confirmErr = "Password do not match";
	}

    if(empty($_POST["assigned_company"])){
        $companyErr = "Company name is required";
    }else{
        $company=explode("|",$_POST["assigned_company"])[0];

    }
    $link = mysqli_connect($hostname, $user, $s_password, $dbname);
   
    if($link === false){echo "potek"; die("ERROR: Could not connect. " . mysqli_connect_error());}
    $sql = "INSERT INTO users (first_name, middle_name, last_name, gender, user_unit, user_level, email_address, company, username, password) values ('$first_name','$middle_name','$last_name','$gender','$user_unit','$user_level','$email','$company','$username','$password')";
    if(mysqli_query($link, $sql)){
        $result =  "Records inserted successfully.";
        header("Refresh:1; url=home.php");
    } else{
        $result = "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }

    mysqli_close($link);
}
?>
<style>
	.error{color:Red;}
</style>


<section id="cover" class="min-vh-100">
    <div id="cover-caption">
        <div class="container">
            <div class="row ">
                <div class="col-xl-5 col-lg-6 col-md-8 col-sm-10 mx-auto text-center form p-4">
                    <h1 class="display-4 py-2 text-truncate">Registration</h1>
                    <div class="px-2">
                        <form method="POST" class="justify-content-center">
                            <div class="form-group has-error has-feedback">
                                <input type="text" name="first_name" id="first_name" class="form-control"  placeholder="First Name" value="<?php echo $first_name; ?>"><span class="error"><?php echo $first_nameErr; ?></span>
                            </div>
                            <div class="form-group">
                                <input type="text" name="middle_name" id="middle_name" class="form-control"  placeholder="Middle Name" value = "<?php echo $middle_name; ?>"><span class="error"><?php echo $middle_nameErr; ?></span>
                            </div>
                            <div class="form-group">
                                <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" value = "<?php echo $last_name; ?>"><span class="error"><?php echo $last_nameErr; ?></span>
                            </div>
                            <div class="form-group">
                            	<select name="gender" id="gender" class="form-control">
                            		<option name="gender" value="">Select Gender</option><span class="error"><?php echo $genderErr; ?></span>
                            		<option name="gender" <?php if ($gender=="Male"){echo "selected";} ?> value="Male">Male</option>
                            		<option name="gender" <?php if ($gender=="Female"){echo "selected";} ?> value="Female">Female</option>
                            	</select>
                            </div>
                            <div class="form-group">
                            	<select name="user_unit" id="user_unit" class="form-control">
                            		<option name="user_unit"  value="">Select User Unit</option>
                            		<option name="user_unit" <?php if ($user_unit=="Sales Documentation"){echo "selected";} ?> value="Sales Documentation">Sales Documentation</option>
                            		<option name="user_unit" <?php if ($user_unit=="Billing & Collection"){echo "selected";} ?> value="Billing & Collection">Billing & Collection</option>
                            		<option name="user_unit" <?php if ($user_unit=="Customer Service"){echo "selected";} ?> value="Customer Service">Customer Service</option>
                            		<option name="user_unit" <?php if ($user_unit=="Data Control"){echo "selected";} ?> value="Data Control">Data Control</option>
                            	</select>
                            </div>
                            <div class="form-group">
                            	<select name="user_level" id="user_level" class="form-control" >
                            		<option name="user_level" value="" >Select User Level</option>
                            		<option name="user_level" <?php if ($user_level=="Employee"){echo "selected";} ?> value="Employee">Employee</option>
                            		<option name="user_level" <?php if ($user_level=="Approver"){echo "selected";} ?> value="Approver">Approver</option>
                            		<option name="user_level" <?php if ($user_level=="Administrator - DC Assistant"){echo "selected";} ?> value="Administrator - DC Assistant">Administrator - DC Assistant</option>
                            		<option name="user_level" <?php if ($user_level=="Administrator - DC Head"){echo "selected";} ?> value="Administrator - DC Head">Administrator - DC Head</option>
                            	</select>
                            </div>
                            <div class="form-group">
                                <input type="text" name="email" id="email" class="form-control" placeholder="Email Address" value = "<?php echo $email; ?>"><span class="error"><?php echo $emailErr; ?></span>
                            </div>
                             <div class="form-group">
                              <select name="assigned_company" id="assigned_company" class="form-control" >
                                <option name="assigned_company" value="" >Select Company Name</option>
                                <?php
                                    if($result){
                                      while($row=mysqli_fetch_array($result)){
                                        $c_name = $row ["$column_name"];
                                        $c_code = $row["$column_code"];
                                        echo "<option>$c_code | $c_name<br></option>";

                                      }
                                    }
                                ?>
                                
                              </select>
                            </div>
                            <div class="form-group">
                                <input type="text" name="username" id="username" class="form-control" placeholder="Username" value = "<?php echo $username; ?>"><span class="error"><?php echo $usernameErr; ?></span>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" value = "<?php echo $password; ?>"><span class="error"><?php echo $passwordErr; ?></span>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Confirm Password" value = "<?php echo $password_confirm; ?>"><span class="error"><?php echo $password_confirmErr; ?></span>
                            </div>
                            <button type="submit" name= "btnRegister" class="btn btn-info btn-block">Sign Up</button>
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
  	
</html>